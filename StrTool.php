<?php
class Str {
	/**
	 +----------------------------------------------------------
	 * 构造do基类,像jquery一样能连续调用
	 +----------------------------------------------------------
	 * @return StrDo
	 +----------------------------------------------------------
	*/	
    static public function do($strdata)
	{
		return new StrDo($strdata);
	}

    /**
     +----------------------------------------------------------
     * 生成UUID 单机使用
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    static public function uuid() {
        $charid = md5(uniqid(mt_rand(), true));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
               .substr($charid, 0, 8).$hyphen
               .substr($charid, 8, 4).$hyphen
               .substr($charid,12, 4).$hyphen
               .substr($charid,16, 4).$hyphen
               .substr($charid,20,12)
               .chr(125);// "}"
        return $uuid;
   	}

	/**
	 +----------------------------------------------------------
	 * 生成Guid主键
	 +----------------------------------------------------------
	 * @return Boolean
	 +----------------------------------------------------------
	 */
	static public function keyGen() {
		return str_replace('-','',substr(Str::uuid(),1,-1));
	}
 
	/**
	 +----------------------------------------------------------
	 * 检查字符串是否是UTF8编码
	 +----------------------------------------------------------
	 * @param string $string 字符串
	 +----------------------------------------------------------
	 * @return Boolean
	 +----------------------------------------------------------
	 */
	static public function isUtf8($str) {
		$c=0; $b=0;
		$bits=0;
		$len=strlen($str);
		for($i=0; $i<$len; $i++){
			$c=ord($str[$i]);
			if($c > 128){
				if(($c >= 254)) return false;
				elseif($c >= 252) $bits=6;
				elseif($c >= 248) $bits=5;
				elseif($c >= 240) $bits=4;
				elseif($c >= 224) $bits=3;
				elseif($c >= 192) $bits=2;
				else return false;
				if(($i+$bits) > $len) return false;
				while($bits > 1){
					$i++;
					$b=ord($str[$i]);
					if($b < 128 || $b > 191) return false;
					$bits--;
				}
			}
		}
		return true;
	}
 
	/**
	 +----------------------------------------------------------
	 * 字符串截取，支持中文和其他编码
	 +----------------------------------------------------------
	 * @static
	 * @access public
	 +----------------------------------------------------------
	 * @param string $str 需要转换的字符串
	 * @param string $start 开始位置
	 * @param string $length 截取长度
	 * @param string $charset 编码格式
	 * @param string $suffix 截断显示字符
	 +----------------------------------------------------------
	 * @return string
	 +----------------------------------------------------------
	 */
	static public function msubstr($str, $start=0, $length=0, $charset="utf-8", $suffix=true) {
        if(function_exists("mb_substr"))
            $slice = mb_substr($str, $start, $length, $charset);
        elseif(function_exists('iconv_substr')) {
            $slice = iconv_substr($str,$start,$length,$charset);
        }else{
            $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
            $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
            $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
            $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
            preg_match_all($re[$charset], $str, $match);
            $slice = join("",array_slice($match[0], $start, $length));
        }
        return $suffix ? $slice.'...' : $slice;
    }
 
	/**
	 +----------------------------------------------------------
	 * 产生随机字串，可用来自动生成密码
	 * 默认长度6位 字母和数字混合 支持中文
	 +----------------------------------------------------------
	 * @param string $len 长度
	 * @param string $type 字串类型
	 * 0 字母 1 数字 其它 混合
	 * @param string $addChars 额外字符
	 +----------------------------------------------------------
	 * @return string
	 +----------------------------------------------------------
	 */
	static public function random($len=6,$type='',$addChars='') {
		$str ='';
		switch($type) {
			case 0:
				$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.$addChars;
				break;
			case 1:
				$chars= str_repeat('0123456789',3);
				break;
			case 2:
				$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZ'.$addChars;
				break;
			case 3:
				$chars='abcdefghijklmnopqrstuvwxyz'.$addChars;
				break;
			case 4:
				$chars = "们阿斯顿发生地方阿斯蒂芬阿斯蒂芬ASF地V字形陈V手势地方过水电费v鬼地方乖宝宝双方都 山东饭馆山东饭馆撒地方v睡的地方改水电费撒地方感受到法国地神风怪盗搞活动大发光火地方馆地方高大发光火的反光板的广播台地方馆别DVB大小分割撒地方 啥地方告诉对方释放掉豆沙方糕撒地方干点啥风格白癜风锅煽豆腐山东饭馆时代复分高速钢微软消费税大发光火好的锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航衣孙龄岭骗休借".$addChars;
				break;
			default :
				// 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
				$chars='ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789'.$addChars;
				break;
		}
		if($len>10 ) {//位数过长重复字符串一定次数
			$chars= $type==1? str_repeat($chars,$len) : str_repeat($chars,5);
		}
		if($type!=4) {
			$chars   =   str_shuffle($chars);
			$str     =   substr($chars,0,$len);
		}else{
			// 中文随机字
			for($i=0;$i<$len;$i++){
			  $str.= self::msubstr($chars, floor(mt_rand(0,mb_strlen($chars,'utf-8')-1)),1,'utf-8',false);
			}
		}
		return $str;
	}
 
	/**
	 +----------------------------------------------------------
	 * 生成一定数量的随机数，并且不重复
	 +----------------------------------------------------------
	 * @param integer $number 数量
	 * @param string $len 长度
	 * @param string $type 字串类型
	 * 0 字母 1 数字 其它 混合
	 +----------------------------------------------------------
	 * @return string
	 +----------------------------------------------------------
	 */
	static public function buildCountRand ($number,$length=4,$mode=1) {
			if($mode==1 && $length<strlen($number) ) {
				//不足以生成一定数量的不重复数字
				return false;
			}
			$rand   =  array();
			for($i=0; $i<$number; $i++) {
				$rand[] =   self::random($length,$mode);
			}
			$unqiue = array_unique($rand);
			if(count($unqiue)==count($rand)) {
				return $rand;
			}
			$count   = count($rand)-count($unqiue);
			for($i=0; $i<$count*3; $i++) {
				$rand[] =   self::random($length,$mode);
			}
			$rand = array_slice(array_unique ($rand),0,$number);
			return $rand;
	}
 
	/**
	 +----------------------------------------------------------
	 *  带格式生成随机字符 支持批量生成
	 *  但可能存在重复
	 +----------------------------------------------------------
	 * @param string $format 字符格式
	 *     # 表示数字 * 表示字母和数字 $ 表示字母
	 * @param integer $number 生成数量
	 +----------------------------------------------------------
	 * @return string | array
	 +----------------------------------------------------------
	 */
	static public function buildFormatRand($format,$number=1) {
		$str  =  array();
		$length =  strlen($format);
		for($j=0; $j<$number; $j++) {
			$strtemp   = '';
			for($i=0; $i<$length; $i++) {
				$char = substr($format,$i,1);
				switch($char){
					case "*"://字母和数字混合
						$strtemp   .= Str::random(1);
						break;
					case "#"://数字
						$strtemp  .= Str::random(1,1);
						break;
					case "$"://大写字母
						$strtemp .=  Str::random(1,2);
						break;
					default://其他格式均不转换
						$strtemp .=   $char;
						break;
			   }
			}
			$str[] = $strtemp;
		}
 
		return $number==1? $strtemp : $str ;
	}
 
	/**
	 +----------------------------------------------------------
	 * 获取一定范围内的随机数字 位数不足补零
	 +----------------------------------------------------------
	 * @param integer $min 最小值
	 * @param integer $max 最大值
	 +----------------------------------------------------------
	 * @return string
	 +----------------------------------------------------------
	 */
	static public function randNumber ($min, $max) {
		return sprintf("%0".strlen($max)."d", mt_rand($min,$max));
	}
 
    // 自动转换字符集 支持数组转换
    static public function autoCharset($string, $from='gbk', $to='utf-8') {
        $from = strtoupper($from) == 'UTF8' ? 'utf-8' : $from;
        $to = strtoupper($to) == 'UTF8' ? 'utf-8' : $to;
        if (strtoupper($from) === strtoupper($to) || empty($string) || (is_scalar($string) && !is_string($string))) {
            //如果编码相同或者非字符串标量则不转换
            return $string;
        }
        if (is_string($string)) {
            if (function_exists('mb_convert_encoding')) {
                return mb_convert_encoding($string, $to, $from);
            } elseif (function_exists('iconv')) {
                return iconv($from, $to, $string);
            } else {
                return $string;
            }
        } elseif (is_array($string)) {
            foreach ($string as $key => $val) {
                $_key = self::autoCharset($key, $from, $to);
                $string[$_key] = self::autoCharset($val, $from, $to);
                if ($key != $_key)
                    unset($string[$key]);
            }
            return $string;
        }
        else {
            return $string;
        }
    }
}

class StrDo{
	private string $strdata="";
    public function __construct($value) {
        $this->strdata=$value;
    }	

	public function __toString()
	{
		return $this->strdata;
	}

	public function toString()
	{
		return $this->__toString();
	}

	public function md5()
	{
		$from= $this->strdata;
		return new StrDo($from);
	}

	public function base64_encode()
	{
		$from= $this->strdata;
		return new StrDo($from);
	}
	public function base64_decode()
	{
		$from= $this->strdata;
		return new StrDo($from);
	}

	public function GBK()
	{
		$from= $this->strdata;
		return new StrDo($from);
	}

	public function UTF8()
	{
		$from= $this->strdata;
		return new StrDo($from);		
	}

	public function BIG5()
	{
		$from= $this->strdata;
		return new StrDo($from);	
	}

	public function replace($find,$dest)
	{
		$from= $this->strdata;
		$from=str_replace($find,$dest,$from);
		return new StrDo($from);			
	}

	public function lower()
	{
		$from= strtolower ($this->strdata);
		return new StrDo($from);		
	}

	public function upper()
	{
		$from= strtoupper($this->strdata);
		return new StrDo($from);		
	}	

	public function trim()
	{
		$from= trim($this->strdata);
		return new StrDo($from);		
	}

	public function nohtml()
	{
		$from=strip_tags($this->strdata);
		return new StrDo($from);		
	}	

	public function del(string ...$dellist)
	{
		$from=$this->strdata;
		foreach($dellist as $del)
		{
			$from= str_replace($del,'',$from);
		}
		return new StrDo($from);
	}	

	public function cut($start,$end,$lt=false,$gt=false){
		$from= $this->strdata;
		$str = explode($start,$from);         
		if (isset($str['1']) and $str['1']!=""){
			$str = explode($end,$str['1']);
			$strs = $str['0'];
		}else{
			$strs="";
		}
		
		if($lt){ $strs = $start.$strs; } 
		if($gt){ $strs = $strs.$end; }
		return new StrDo($strs);
	}	
}
?>