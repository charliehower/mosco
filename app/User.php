<?php

namespace App;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

class User extends Authenticatable
{

    public $timestamps = false;
    private $_titles;
    private $_scores;
    protected $primaryKey = 'id';

    public function __construct(){
        
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'daban',
        'class',
        'password'
    ];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    /**
     * 属于该用户的头衔
     *
     */
    public function titles(){
        if($this->_titles)return $this->_titles;
        $this->_titles=$this->belongsToMany('App\Title')->withPivot(['title','rank','score','time'])->get()->sortBy('rank');
        return $this->_titles;
    }
    /**
     * 该用户的第一个职位
     *
     */
    public function title(){
        $t=$this->titles();
        if($t->count())return $t[0]->name;
        return "无";
    }
    /**
     * 该用户的任职时间
     *
     */
    public function worktime(){
        $t=$this->_titles;

        if($t->count())
            return $t[0]->pivot->time;
        return 0;
    }
    /*
    * 属于该用户的头衔的等级
    */
    public function rank(){
        $t=$this->titles();
        if($t->count())return $t[0]->rank;
        return 99;
    }
    public function dy($i){
        if($i->rank()==$this->rank()||
            $i->rank()!=4&&$i->rank()!=7||
            $this->rank()!=4&&$this->rank()!=7)return 0;
        if($i->titles()[0]->dy==$this->titles()[0]->dy)return 1;
        return 0;
    }
    /*
     *  判断我是否给ta打学生工作分
     *  是则返回打分起点（1.6、2.6……,否则返回0
     * title rank score
     * 辅导员   1
     * 大班长   2  3.6
     * 大班团支书3  3.6
     * 大班委   4  2.6
     * 小班长   5  2.6
     * 小班团支书6  2.6
     * 小班委   7  1.6
    */
    public function toScore($ta){
        $mr=$this->rank();
        if(!$ta){
            //返回我的职位的起点分
            if($mr<4)return 1;
            if($mr<7)return 1;
            if($mr==7||$mr==10)return 1;
            return 0;
        }
        $tr=$ta->rank();
        $m=$this->title();$t=$ta->title();

        //不能是自己，不能是不同大班的，不能是辅导员
        if($ta->id==$this->id||$tr==1)
            return 0;


        if($m=='辅导员'){
            if($tr<4)return 1;
            if($tr<7)return 1;
        }else if($m=='无'||$m=='宿舍长'){
            if($ta->class == $this->class){
                if($t=='小班长'||$t=='小班团支书')
                    return 1;
                if($tr==7)
                    return 1;
            }
        }else if($m=='大班长'){
            if($t=='小班长'||$tr==4)//本班的小班委
                return 1;
            if($tr==7 && $ta->class == $this->class)
                return 1;
        }else if($m=='大班团支书'){
            if($t=='小班团支书'||$tr==4)
                return 1;
            if($tr==7 && $ta->class == $this->class)
                return 1;
        }else if($m=='小班长'){
            if($t=='大班长')
                return 1;
            if($tr==7 && $ta->class == $this->class)
                return 1;
            if($t=='小班团支书' && $ta->class == $this->class)
                return 1;
        }else if($m=='小班团支书'){
            if($t=='大班团支书')
                return 1;
            if($ta->class == $this->class && $tr==7)
                return 1;
        }else if($mr==4){//我是大班委
            if($t=='大班长' || $t=='大班团支书')
                return 1;
            if($this->dy($ta))
                return 1;
            if($ta->class == $this->class && $tr==7)
                return 1;
        }else if($mr==7){//我是小班委
            if($ta->class == $this->class && ($t=='小班长'||$t=='小班团支书'))
                return 1;
            if($this->dy($ta))
                return 1;
            if($ta->class == $this->class && $tr==7)
                return 1;
        }
        return 0;
    }
    /**
     * 属于该用户的打分
     *
     */
    public function scores($can=null,$nav=null){

        if(!$this->_scores){
            $this->_scores=$this->hasMany('App\Score')->get();
        }

        if($can&&$nav){
           
            foreach ($this->_scores as $key => $value) {
                if($value->candiate==$can && $value->nav==$nav)
                {
                    return $value;
                }
            }
            return null;
        }
        return $this->_scores;
    }
    /**
     * 该用户某项是否完成
     *
     */
    public function complish($i){
        if($res=$this->hasMany('App\Result')->where('nav_id',$i->id)->first())
            return $res->finish;
        return false;
    }
    /*
    * 该用户完成的项目数
    */
    public function comnum()
    {
        $ans=0;
        foreach(Nav::All() as $nav){
            if($this->complish($nav))
                ++$ans;
        }
        return $ans;
    }
    /**
     * 该用户某项的得分
     * 因为只记录了打的非满分，和打分人数（包括满分和非满分）。
     打满分人数=打分人数-打分记录数目
     得分=（满分*打满分人数+非满分总分）/打分人数
        =（满分*（打分人数-打分记录数目）+非满分总分）/打分人数
        = 满分+（非满分总分-满分*打分记录数）/打分人数
     */
    public function result($i){
        if($res=$this->hasMany('App\Result')->where('nav_id',$i->id)->first()){
            if($res->cnt){
                if($i->ename=="sports"){
                    $sco=Score::where('candiate',$this->id)
                    ->where('nav', 'like', $i->id.'_%')->get()
                    ->sum('score');
                    return min($sco,10);//文娱体育上限
                }
                $dsco=$this->defsco(0,$i);//满分
                $msco=Score::where('candiate',$this->id)->where('nav',$i->id);//打分记录

                $sco=$dsco+
                (($msco->get()->sum('score')) - ($msco->count()*$dsco))/$res->cnt;
                
                if($i->ename=="work"){
                    if($tit=$this->titles()->first()){
                        return $sco*$tit->pivot->time;
                    }
                    return 0;
                }
                return $sco;
            }
        }
        return 0;
    }
    //德育分
    public function mosco(){
        $mosco=0;
        $work=0;
        foreach(Nav::All() as $n)
            if($n->ename=='work'||$n->ename=='xueshenghui')
                $work=max($work,$this->result($n));
            else
                $mosco+=$this->result($n);

        return $mosco+$work;
    }
    /*
     * 默认分数
     */
    public function defsco($ta,$nav){
        if(strstr($nav->id,'_'))return 0;

        if($nav->ename=='xueshenghui'||$nav->ename=='competition'||$nav->ename=='sports')
            return 0;//学生会、竞赛不在这里计算
        if($nav->ename=='work')
            return 5;//学生工作加分的默认最高分=5
       return $nav->end;//默认最高分
    }
    /*
     * 我给ta的i号项目打分时默认选中的值（可能是打过的分数
     */
    public function oldval($ta,$nav,$navid=0){
        if($navid==0)$navid=$nav->id;
        if($t=$this->scores($ta->id,$navid)){//打分记录里有记录
            return $t->score;
        }

        if($nav->ename=='competition')
            return $this->award();//竞赛奖项

        return $this->defsco($ta,$nav);//默认值
    }
    /*
    *   显示奖项
    */
    public function award()
    {
        $id=Nav::where('ename','competition')->first()->id;
        if(Result::where('user_id',$this->id)
            ->where('nav_id',$id)->count())
        return Result::where('user_id',$this->id)
            ->where('nav_id',$id)->first()->award;
        return null;
    }
}
