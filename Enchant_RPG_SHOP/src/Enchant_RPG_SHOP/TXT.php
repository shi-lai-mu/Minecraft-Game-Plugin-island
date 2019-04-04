<?php
namespace Enchant_RPG_SHOP\Enchant;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginLoader;
use pocketmine\plugin\PluginBase;

use Enchant_RPG_SHOP\Enchant_RPG_SHOP as Main;

class TXT
{
	private $Main;

	public function __construct(Main $Main)
	{
		$this->Main = $Main;
		$dir = $this->Main->getDataFolder();
		$this->start();
	}
	
	public function start()
	{
		$dir = $this->Main->getDataFolder();
		@mkdir($dir);
		@mkdir($dir . 'Prepaid/');
		$this->Prepaid_10 = new Config($dir . 'Prepaid/10.Prepaid',Config::YAML,[]);
		$this->Prepaid_30 = new Config($dir . 'Prepaid/30.Prepaid',Config::YAML,[]);
		$this->Prepaid_50 = new Config($dir . 'Prepaid/50.Prepaid',Config::YAML,[]);
		$this->Prepaid_75 = new Config($dir . 'Prepaid/75.Prepaid',Config::YAML,[]);
		$this->Prepaid_100 = new Config($dir . 'Prepaid/100.Prepaid',Config::YAML,[]);
		$this->Enchant_NBT = new Config($dir . 'Enchant_NBT.yml',Config::YAML,array('Enchant' => 0));
		$this->set = new Config($dir . 'set.yml',Config::YAML,[]);
		$this->b = new Config($dir . 'Config.yml',Config::YAML,[]);
		$this->signs = new Config($dir . 'signs.json',Config::YAML,[]);
		$this->Player = new Config($dir . 'Player.json',Config::YAML,array('attribute' => Array()));
		$this->info = $this->Player->get('attribute');
		$this->beibao = new Config($dir . 'beibao.json',Config::YAML,[]);
		$this->Money = new Config($dir . 'Money.json',Config::YAML,[]);
		$this->item = new Config($dir . 'item.yml',Config::YAML,$this->itemList());
		$this->SHOP = new Config($dir . 'SHOP.yml',Config::YAML,[
		'设置' => [
			'书架附加按钮功能' => True,
			'标签' => ['附魔','回收','强化']
		],
		'附魔' => [] , '购买' => [] , '强化' => [] , '修复' => []  , '回收' => [] , '宝石' => []]);
		$this->Command_Shop = new Config($dir . 'Command_Shop.yml',Config::YAML,array(
			'附魔' => Array(),
			'修复' => Array(),
			'强化' => Array(),
			'出售' => Array(),
			'回收' => Array(),
			'镶嵌' => Array(),
			'RPG' => Array(),
			'已下架' => Array(),
			'信息' => Array(
				'DATA' => 0
			),
		));
		$this->Prepaid = new Config($dir . 'Prepaid.yml',Config::YAML,array(
			'注释' => '请不要修改此文件内的数据,这会导致充值失败!',
			10 => Array(),
			30 => Array(),
			50 => Array(),
			75 => Array(),
			100 => Array()
		));
		$set1 = $this->getConfigTxT();
		if(!$this->set->exists('设置'))
		{
			$this->Main->getLogger()->info('§4正在写入配置信息[' . $set1['配置版本'] . ']');
			$this->set->set('设置',$set1);
			$this->set->save();
		}
		else
		{
			$set = $this->set->get('设置');
			if($set['配置版本'] != $set1['配置版本'])
			{
				$this->Main->getLogger()->info('§4发现旧版本配置文件§6[' . $set['配置版本'] . ']§4版本,正在§c智能覆盖§4此版本§6[' . $set1['配置版本'] . ']§4!');
				$this->Main->getLogger()->info('§4这会尽量保留原设置,从而加入§c新的设置...');
				$as = array_merge($set1,$set);
				$as['配置版本'] = $set1['配置版本'];
				if(strstr($as['底部显示'],'格挡'))
				{
					$this->Main->getLogger()->info('§e版本因特殊原因，正在重置底部设置!');
					$as['底部显示'] = "                                                                                 {动态线}
                                                                                 §4▍  §d{生命} / {生命上限} 生命 ஐ
                                                                                 §4▍  §9{魔法} / {魔法上限}  魔法 ✪
                                                                                 §4▍  §2+{物攻}  物攻 ➹
                                                                                 §4▍  §3+{物防}  物防 ♝
                                                                                 §4▍  §e+{暴击}  暴击 ☄
                                                                                 §4▍  §c+{抗暴}  抗暴 ♋
                                                                                 §4▍  §7+{魔防}  魔防 ♙
                                                                                 §4▍  §a+{魔攻}  魔攻 ☢
                                                                                 {动态线}









";
				}
				$this->set->set('设置',$as);
				$this->set->save();
			}
		}
	}

	public function getConfigTxT()
	{
		return Array(
			'配置版本' => '2.2.0_1',
			'上一个版本' => 0,
			'双击确认' => '开',
			'底部' => '开',
			'底部动态框' => '开',
			'附魔台商店' => '开',
			'白名单内成员才可创建商店' => '关',
			'后台才能执行附魔券操作' => '开',
			'在指定世界开启底部' => '关',
			'无限在未射中目标情况下也生效' => '关',
			'等级影响属性' => '开',
			'只开启血量和攻击属性' => '关',

			'底部世界' => ['世界一','世界二','按照这格式无限加',],
			'白名单' => Array(),
			'最大可扩展血量上限' => 100,
			'每级加血量上限' => 0,
			'每级加物攻' => 0,
			'双击冷却秒数' => 3,
			'卡密账号长度' => 10,
			'卡密密码长度' => 15,
			'等级上限' => 200000,
			'宝石ID' => 264,
			'底部方式' => 'Tip',
			
			'点券名称' => '点券',//D
			'附魔券名称' => '附魔券',//W
			'金币名称' => '金币',//M
			'经验名称' => '经验',//X
			'等级名称' => '等级',//L
			
			'底部显示' => "{动态线}                                                                                 
                                                                                 §4▍  §d{生命} / {生命上限} 生命 ஐ
                                                                                 §4▍  §9{魔法} / {魔法上限}  魔法 ✪
                                                                                 §4▍  §2+{物攻}  物攻 ➹
                                                                                 §4▍  §3+{物防}  物防 ♝
                                                                                 §4▍  §e+{暴击}  暴击 ☄
                                                                                 §4▍  §c+{抗暴}  抗暴 ♋
                                                                                 §4▍  §7+{魔防}  魔防 ♙
                                                                                 §4▍  §a+{魔攻}  魔攻 ☢
                                                                                 {动态线}









",

			'注释' => Array(
				'在下方内写入这些符号会被替换为文本' => 'true',
				'@ID' => '附魔ID名称',
				'@LV' => '附魔等级',
				'@MAX' => '附魔最高等级',
				'@MIX' => '附魔最低等级',
				'@Amount' => '花费的数量',
				'@MC' => '交换物名称',
				'@TS' => '附魔效果注释[仅附魔可用]',
				'@DA' => '附魔耐久变化'
			),

			'拆卸台坐标' => Array(),
			
			'附魔' => Array(
				'§5[§6附魔 §e"@ID"§6 商店§5]',
				'§a附魔等级§e @LV §2LV',
				'§2耗费§1 @Amount @MC§2 §a@DA',
				'@TS'
			),
			
			'强化' => Array(
				'§5[§6强化 §e"@ID"§6 商店§5]',
				'§3强化升§1 @MIX §3LV§1',
				'§2耗费§1 @Amount §2@MC §a@DA',
				'§2可强化上限§6 @MAX 级'
			),
			
			'回收' => Array(
				'§5[§6回收 §e"@ID"§6 商店§5]',
				'§a等级§e @MIX - @MAX §2LV',
				'§2获得§1 @Amount §2@MC',
				'§2装备耐久在§a @DA §2以上'
			),
			
			'修复' => Array(
				'§5[§6修复 §e""@ID"§6 商店§5]',
				'§a等级§e @MIX - @MAX §2LV',
				'§2耗费§1 @Amount §2@MC',
				'§2装备耐久恢复§a @DA'
			),
			
			'出售' => Array(
				'§5[§b出售§6 §e"@ID"§6 商店§5]',
				'§a附魔等级§e @LV §2LV',
				'§2耗费§1 @Amount §2@MC §a@DA',
				'@TS'
			)
		);
	}

	public function itemList()
	{
		return Array(
			1 => '石头',
			2 => '草方块',
			3 => '泥土',
			4 => '圆石',
			98 => '石砖',
			48 => '苔石',
			5 => '橡木木板',
			45 => '砖块',
			243 => '灰化土',
			110 => '菌丝',
			82 => '粘土',
			172 => '硬化粘土',
			159 => '白色染色粘土',
			24 => '沙石',
			24 => '磨制沙石',
			24 => '平滑沙石',
			12 => '沙子',
			13 => '沙砾',
			17 => '橡木',
			162 => '金合欢木',
			162 => '深色橡木',
			112 => '地狱砖块',
			87 => '地狱岩',
			88 => '灵魂沙',
			7 => '基岩',
			67 => '石楼梯',
			53 => '橡木楼梯',
			134 => '云杉木楼梯',
			135 => '桦木楼梯',
			136 => '丛林楼梯',
			163 => '金合欢木楼梯',
			164 => '深色橡木楼梯',
			108 => '砖楼梯',
			128 => '沙石楼梯',
			109 => '石砖楼梯',
			114 => '地狱砖楼梯',
			156 => '石英楼梯',
			44 => '石台阶',
			44 => '圆石台阶',
			158 => '橡木台阶',
			158 => '云杉木台阶',
			158 => '橡木台阶',
			158 => '丛林木台阶',
			158 => '金合欢木台阶',
			158 => '深色橡木台阶',
			44 => '砖台阶',
			44 => '沙石台阶',
			44 => '石砖台阶',
			44 => '石英台阶',
			44 => '地狱砖台阶',
			155 => '石英块',
			155 => '錾制石英块',
			155 => '竖纹石英块',
			16 => '煤矿石',
			15 => '铁矿石',
			14 => '金矿石',
			56 => '钻石矿石',
			21 => '青金石矿石',
			73 => '红石矿石',
			129 => '绿宝石矿石',
			153 => '下界石英矿石',
			49 => '黑曜石',
			79 => '冰',
			174 => '浮冰',
			80 => '雪',
			121 => '末地石',
			165 => '粘液块',
			139 => '圆石墙',
			139 => '苔石墙',
			111 => '睡莲',
			41 => '金块',
			42 => '铁块',
			57 => '钻石块',
			22 => '青金石块',
			173 => '煤炭块',
			133 => '绿宝石块',
			152 => '红石块',
			78 => '顶层雪',
			20 => '玻璃',
			89 => '萤石',
			106 => '藤蔓',
			65 => '梯子',
			19 => '海绵',
			102 => '玻璃板',
			324 => '橡木门',
			427 => '云杉木门',
			428 => '白桦木门',
			429 => '丛林木门',
			430 => '金合欢木门',
			431 => '深色橡木门',
			330 => '铁门',
			96 => '活板门',
			167 => '铁活板门',
			85 => '橡木栅栏',
			85 => '云杉木栅栏',
			85 => '白桦木栅栏',
			85 => '丛林木栅栏',
			85 => '金合欢木栅栏',
			85 => '深色橡木栅栏',
			113 => '地狱砖栅栏',
			107 => '橡木栅栏门',
			184 => '白桦木栅栏门',
			183 => '云杉木栅栏门',
			186 => '深色橡木栅栏门',
			185 => '丛林木栅栏门',
			187 => '金合欢木栅栏门',
			101 => '铁栏杆',
			355 => '床',
			47 => '书架',
			321 => '画',
			389 => '物品展示栏',
			58 => '工作台',
			245 => '切石机',
			54 => '箱子',
			146 => '陷阱箱',
			61 => '熔炉',
			379 => '酿造台',
			380 => '炼药锅',
			25 => '音符盒',
			120 => '末地传送门',
			145 => '铁砧',
			145 => '轻微损坏的铁砧',
			145 => '严重损坏的铁砧',
			37 => '蒲公英',
			38 => '罂粟',
			38 => '兰花',
			38 => '绒球葱',
			38 => '茜草花',
			38 => '红色郁金香',
			38 => '橙色郁金香',
			38 => '白色郁金香',
			38 => '粉色郁金香',
			38 => '滨菊',
			175 => '向日葵',
			175 => '丁香',
			175 => '高草丛',
			175 => '大型蕨',
			175 => '玫瑰丛',
			175 => '牡丹',
			39 => '棕蘑菇',
			40 => '红蘑菇',
			99 => '蘑菇',
			100 => '蘑菇块',
			99 => '蘑菇',
			99 => '蘑菇',
			81 => '仙人掌',
			103 => '西瓜',
			86 => '南瓜',
			91 => '南瓜灯',
			30 => '蜘蛛网',
			170 => '干草块',
			31 => '草',
			31 => '蕨',
			32 => '枯死的灌木',
			6 => '橡木树苗',
			6 => '云杉树苗',
			6 => '白桦树苗',
			6 => '丛林树苗',
			6 => '金合欢树苗',
			6 => '深色橡木树苗',
			18 => '橡树树叶',
			18 => '云杉树叶',
			18 => '白桦树叶',
			18 => '丛林树叶',
			161 => '金合欢树叶',
			161 => '深色橡木树苗',
			354 => '蛋糕',
			397 => '骷髅头颅',
			397 => '凋灵骷髅头颅',
			397 => '僵尸的头',
			397 => '头',
			397 => '爬行者的头',
			323 => '告示牌',
			390 => '花盆',
			52 => '刷怪箱',
			116 => '附魔台',
			35 => '羊毛',
			35 => '淡灰色羊毛',
			35 => '灰色羊毛',
			35 => '黑色羊毛',
			35 => '棕色羊毛',
			35 => '红色羊毛',
			35 => '橙色羊毛',
			35 => '黄色羊毛',
			35 => '黄绿色羊毛',
			35 => '绿色羊毛',
			35 => '青色羊毛',
			35 => '淡蓝色羊毛',
			35 => '蓝色羊毛',
			35 => '紫色羊毛',
			35 => '品红色羊毛',
			35 => '粉色羊毛',
			171 => '地毯',
			171 => '淡灰色地毯',
			171 => '灰色地毯',
			171 => '黑色地毯',
			171 => '品红色地毯',
			171 => '红色地毯',
			171 => '橙色地毯',
			171 => '黄色地毯',
			171 => '黄绿色地毯',
			171 => '绿色地毯',
			171 => '青色地毯',
			171 => '淡蓝色地毯',
			171 => '蓝色地毯',
			171 => '紫色地毯',
			171 => '粉红色地毯',
			66 => '铁轨',
			126 => '激活铁轨',
			28 => '探测铁轨',
			50 => '火把',
			325 => '桶',
			325 => '牛奶',
			325 => '水桶',
			325 => '岩浆桶',
			46 => 'TNT',
			331 => '红石',
			261 => '弓',
			346 => '钓鱼竿',
			259 => '打火石',
			359 => '剪刀',
			347 => '钟',
			345 => '指南针',
			328 => '矿车',
			333 => '橡木船',
			333 => '杉木船',
			333 => '桦木船',
			333 => '木船',
			333 => '相思船',
			333 => '黑橡木船',
			383 => '村民幼体',
			383 => '鸡幼崽',
			383 => '牛幼崽',
			383 => '猪幼崽',
			246 => '发光的黑曜石',
			247 => '下界反映盒',
			383 => '绵羊幼崽',
			383 => '狼幼崽',
			383 => '豹猫幼崽',
			383 => '蘑菇幼体',
			383 => '僵尸幼体',
			383 => '僵尸猪人幼体',
			383 => '鱿鱼幼体',
			383 => '洞穴蜘蛛幼崽',
			383 => '岩浆怪幼体',
			383 => '妖鬼幼体',
			383 => '火焰幼体',
			268 => '木剑',
			290 => '木锄',
			269 => '木锹',
			270 => '木镐',
			271 => '木斧',
			272 => '石剑',
			291 => '石锄',
			273 => '石锹',
			274 => '石镐',
			275 => '石斧',
			267 => '铁剑',
			292 => '铁锄',
			256 => '铁锹',
			257 => '铁镐',
			258 => '铁斧',
			276 => '钻石剑',
			293 => '钻石锄',
			277 => '钻石锹',
			278 => '钻石镐',
			279 => '钻石斧',
			283 => '金剑',
			294 => '金锄',
			284 => '金锹',
			285 => '金镐',
			286 => '金斧',
			298 => '皮革帽子',
			299 => '皮革外套',
			300 => '皮革裤子',
			301 => '皮革靴子',
			302 => '锁链头盔',
			303 => '锁链胸甲',
			304 => '锁链护腿',
			305 => '锁链靴子',
			306 => '铁头盔',
			307 => '铁胸甲',
			308 => '铁护腿',
			309 => '铁靴子',
			310 => '钻石头盔',
			311 => '钻石护甲',
			312 => '钻石护腿',
			313 => '钻石靴子',
			314 => '金头盔',
			315 => '金胸甲',
			316 => '金护腿',
			317 => '金靴子',
			69 => '拉杆',
			123 => '红石灯',
			76 => '红石火把',
			72 => '木质压力板',
			70 => '石质压力板',
			147 => '测重压里板',
			148 => '测重压力板',
			143 => '按钮',
			77 => '按钮',
			151 => '阳光传感器',
			131 => '绊线钩',
			365 => '生鸡肉',
			125 => '投掷器',
			23 => '发射器',
			332 => '雪球',
			263 => '煤炭',
			263 => '木炭',
			264 => '钻石',
			265 => '铁锭',
			266 => '金锭',
			388 => '绿宝石',
			280 => '木棍',
			281 => '碗',
			287 => '线',
			288 => '羽毛',
			318 => '燧石',
			334 => '皮革',
			415 => '兔子皮',
			353 => '糖',
			406 => '下界石英',
			339 => '纸',
			360 => '西瓜片',
			262 => '箭',
			352 => '骨头',
			338 => '甘蔗',
			296 => '小麦',
			295 => '小麦种子',
			361 => '南瓜种子',
			362 => '西瓜种子',
			458 => '甜菜根种子',
			260 => '苹果',
			466 => '金苹果',
			349 => '生鱼',
			460 => '生鲑鱼',
			461 => '小丑鱼',
			462 => '河豚',
			463 => '熟鲑鱼',
			367 => '腐肉',
			282 => '蘑菇煲',
			297 => '面包',
			319 => '生猪肉',
			320 => '熟猪肉',
			366 => '熟鸡肉',
			363 => '生牛肉',
			364 => '牛排',
			391 => '胡萝卜',
			392 => '马铃薯',
			393 => '烤马铃薯',
			394 => '毒马铃薯',
			357 => '曲奇',
			400 => '南瓜派',
			411 => '生兔肉',
			412 => '熟兔肉',
			413 => '兔肉煲',
			378 => '岩浆膏',
			369 => '烈焰棒',
			371 => '金粒',
			396 => '金萝卜',
			382 => '闪烁的西瓜',
			414 => '兔子腿',
			370 => '恶魂之泪',
			341 => '粘液球',
			377 => '烈焰粉',
			372 => '地狱疣',
			289 => '火药',
			348 => '萤石粉',
			375 => '蜘蛛眼',
			376 => '发酵蛛眼',
			384 => '附魔之瓶',
			351 => '红玫瑰',
			351 => '灰色染料',
			351 => '淡灰色染料',
			351 => '骨粉',
			351 => '淡蓝色染料',
			351 => '橙色染料',
			351 => '青金石',
			351 => '紫色染料',
			351 => '品红色染料',
			351 => '粉红色染料',
			351 => '可可豆',
			351 => '蒲公英黄',
			351 => '黄绿色染料',
			351 => '仙人掌绿',
			351 => '青色染料',
			373 => '夜视药水',
			373 => '隐身药水',
			373 => '跳跃药水'
		);
	}
}