<?php
date_default_timezone_set('Asia/Tokyo');
class cron
{
    public function __construct(mixed $filepath = "")
    {
        $val = @file_get_contents($this->pval($filepath));
        $obj_ = @json_decode($this->pval($val));
        $obj = (object)[];
        foreach ($obj_ as $key => $value) {
            $obj->name = "month";
            $obj->val = $value->m;
            if ($flg = $this->trigger_check($obj,"m",1,12)) {
                $obj->name = "day";
                $obj->val = $value->d;
                if ($flg = $this->trigger_check($obj,"d",1,31)) {
                    $obj->name = "hour";
                    $obj->val = $value->H;
                    if ($flg = $this->trigger_check($obj,"H",0,23)) {
                        $obj->name = "minutes";
                        $obj->val = $value->i;
                        if ($flg = $this->trigger_check($obj,"i",0,59)) {
                            $obj->name = "week";
                            $obj->val = implode(",", $value->w);
                            if ($flg = $this->trigger_check($obj,"w",0,0)) {
                                $this->command($value->command);
                            }
                        }
                    }
                }
            }
        }
    }
    public function command(mixed $command_val = "")
    {
        $command_val = $this->pval($command_val);
        exec($command_val . " > /dev/null &");
        // print "よろしくお願いします～～～!!".PHP_EOL;
        return true;
    }

    public function pval(mixed $val = "")
    {
        if (is_array($val)) {
            foreach ($val as $key => $value) {
                $val[$key] = strip_tags($value);
            }
        } else {
            $val = strip_tags($val);
        }
        return $val;
    }

    public function trigger_check(mixed $variable = "",mixed $d="",int $min=0 ,int $max=0)
    {
        if (!$variable) return false;
        if ($variable->val === "*") return true;
        switch ($variable->name) {
            case 'week':
                $value = @explode(",", $variable->val);
                return (int)$value[(int)date($d)] === 1 ? true : false;
                break;
            default:
                if (preg_match("/^(\*\/[0-9]{1,})$/", $variable->val)) {
                    $value = @explode("*/", $variable->val)[1];
                    if (is_numeric($value) && $value >= $min && $value <= $max) {
                        return (int)date($d) % $value === 0 ? true : false;
                    }
                }
                if (preg_match("/^([0-9]{1,}\,{1,})/", $variable->val)) {
                    $value = @explode(",", $variable->val);
                    $value = array_map('intval', $value);
                    return in_array((int)date($d), $value, true) === true ? true : false;
                }

                $value = (int)$variable->val;
                if (is_numeric($value) && $value >= $min && $value <= $max) {
                    return $value === (int)date($d) ? true : false;
                }
                return false;
                break;
        }
        return false;
    }
}

if($argv[1]){
    //argv
    new cron($argv[1]);
}

