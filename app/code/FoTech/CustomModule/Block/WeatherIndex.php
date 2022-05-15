<?php
namespace FoTech\CustomModule\Block;
use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;

class WeatherIndex extends Template implements BlockInterface
{

    protected $_template = "weather_index.phtml";

    public function __construct(\Magento\Framework\View\Element\Template\Context $context)
    {
        parent::__construct($context);
	$om = \Magento\Framework\App\ObjectManager::getInstance();
 	$page = $om->get('Magento\Framework\View\Page\Config');
 	$page->addPageAsset('FoTech_CustomModule::css/weather_style.css');
    }

    public function getWeatherInfoIn($cityName): array
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://api.openweathermap.org/data/2.5/weather?q=$cityName&appid=20b25373234ba5126e0a80eb364fbaa7");
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($curl);

        $data = json_decode($result, TRUE);
        curl_close($curl);
        $res['name'] = $data['name'];
        $res['main'] = $data['weather'][0]['main'];
        $res['temp'] = round($data['main']['temp'] - 273.15, 1);
        $res['wind'] = round($data['wind']['speed'], 1);
        $res['clouds'] = $data['clouds']['all'];
        $res['humidity'] = $data['main']['humidity'];
        return $res;
    }
}
