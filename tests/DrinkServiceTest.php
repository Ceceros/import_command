<?php declare(strict_types=1);

use App\Entity\Drink;
use App\Service\DrinkService;
use PHPUnit\Framework\TestCase;

final class DrinkServiceTest extends TestCase
{
    public function testXmlToDrink(): void
    {
        $row=new SimpleXMLElement('<item><entity_id>1136</entity_id><CategoryName><![CDATA[New York Coffee Beans]]></CategoryName><sku>NYCKenyaDecaf5lbBean</sku><name><![CDATA[New York Coffee Kenya AA Decaf Coffee Beans 5lb Bag]]></name>
        <description></description><shortdesc><![CDATA[New York Coffee Kenya AA Decaf Coffee Beans 5lb Bag bulk quantity makes many pots of full-flavored decaffeinated coffee from New York Coffee Beans.]]></shortdesc><price>69.9900</price>
        <link>http://www.coffeeforless.com/new-york-coffee-kenya-aa-decaf-coffee-beans-5lb-bag.html</link><image>http://mcdn.coffeeforless.com/media/catalog/product/images/uploads/product/NYC_Coffee_Bag_whitebg.png</image>
        <Brand><![CDATA[New York Coffee]]></Brand><Rating>0</Rating><CaffeineType>Decaffeinated</CaffeineType><Count>1</Count><Flavored>No</Flavored><Seasonal>No</Seasonal><Instock>Yes</Instock><Facebook>1</Facebook><IsKCup>0</IsKCup></item>'
        );
        
        $drinkService= new DrinkService();
        $drink= $drinkService->xmltoDrink($row);

        $this->assertInstanceOf(
            Drink::class,
            $drink
        );

        $this->assertEquals(
            1136,
            $drink->getId()
        );

        $this->assertEquals(
            'Decaffeinated',
            $drink->getCaffeine()
        );
    }
    
}
