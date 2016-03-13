<?php
namespace IpType\Test\Fixture;

use Cake\Database\Type;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use IpType\Database\Type\IpType;
use TestApp\Lib\TestClass;
use TestApp\Model\Table\IpsTable;

/**
 * This class helps in indirectly testing the functionalities of IntegrationTestCase
 *
 * @property IpsTable Ips
 */
class IpsTestCase extends TestCase
{

    public $fixtures = ['plugin.ip_type.ips'];

    /**
     * Setup the test case, backup the static object values so they can be restored.
     * Specifically backs up the contents of Configure and paths in App if they have
     * not already been backed up.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->Ips = TableRegistry::get('TestApp.Ips');
        $this->Ips->schema()->columnType('ip', 'ip');
    }

    public function testMethodFindByIp()
    {
        $ips = $this->Ips->find('all', ['conditions' => ['ip' => '192.168.178.1']])->all()->toArray();
        $this->assertCount(1, $ips);
        $this->assertTextEquals('192.168.178.1', $ips[0]->ip);
    }

    public function testMethodDefaultMethod()
    {
        $ips = $this->Ips->find('all', ['conditions' => ['type' => 'default']])->all()->toArray();
        $this->assertCount(2, $ips);
        $this->assertTextEquals('127.0.0.1', $ips[0]->ip);
        $this->assertTextEquals('::1', $ips[1]->ip);

        $ip = $this->Ips->newEntity(['ip' => '127.0.0.2']);
        $this->Ips->save($ip);
        $this->Ips->schema()->columnType('ip', 'string');
        $ip = $this->Ips->get($ip->id);
        $this->assertTextEquals(inet_pton('127.0.0.2'), $ip->ip);
    }

    public function testMethodNameMethod()
    {
        /** @var IpType $ipType */
        $ipType = Type::build('ip');
        $ipType->_encode = 'ip2long';
        $ipType->_decode = 'long2ip';

        $ips = $this->Ips->find('all', ['conditions' => ['type' => 'methodName']])->all()->toArray();
        $this->assertCount(2, $ips);
        $this->assertTextEquals('127.0.0.1', $ips[0]->ip);
        $this->assertTextEquals('', $ips[1]->ip); //ip2long doesn't support ipv6 adresses

        $ip = $this->Ips->newEntity(['ip' => '127.0.0.2']);
        $this->Ips->save($ip);
        $this->Ips->schema()->columnType('ip', 'string');

        $ip = $this->Ips->get($ip->id);
        $this->assertTextEquals(ip2long('127.0.0.2'), $ip->ip);
    }

    public function testMethodCallableMethod()
    {
        /** @var IpType $ipType */
        $ipType = Type::build('ip');
        $ipType->_encode = function ($value) {
            return $value . '1';
        };
        $ipType->_decode = function ($value) {
            return $value;
        };

        $ips = $this->Ips->find('all', ['conditions' => ['type' => 'callable']])->all()->toArray();
        $this->assertCount(2, $ips);
        $this->assertTextEquals('127.0.0.11', $ips[0]->ip);
        $this->assertTextEquals('::11', $ips[1]->ip);

        $ip = $this->Ips->newEntity(['ip' => '127.0.0.2']);
        $this->Ips->save($ip);
        $this->Ips->schema()->columnType('ip', 'string');

        $ip = $this->Ips->get($ip->id);
        $this->assertTextEquals(call_user_func($ipType->_encode, '127.0.0.2'), $ip->ip);
    }

    public function testMethodObjectCallableMethod()
    {
        /** @var IpType $ipType */
        $ipType = Type::build('ip');
        $object = new \stdClass();
        $object->_encode = function ($value) {
            return $value . '1';
        };
        $object->_decode = function ($value) {
            return $value;
        };
        $ipType->_encode = $object->_encode;
        $ipType->_decode = $object->_decode;

        $ips = $this->Ips->find('all', ['conditions' => ['type' => 'callable']])->all()->toArray();
        $this->assertCount(2, $ips);
        $this->assertTextEquals('127.0.0.11', $ips[0]->ip);
        $this->assertTextEquals('::11', $ips[1]->ip);

        $ip = $this->Ips->newEntity(['ip' => '127.0.0.2']);
        $this->Ips->save($ip);
        $this->Ips->schema()->columnType('ip', 'string');

        $ip = $this->Ips->get($ip->id);
        $this->assertTextEquals(call_user_func($ipType->_encode, '127.0.0.2'), $ip->ip);
    }

    public function testMethodObjectWrapperFunctionMethod()
    {
        /** @var IpType $ipType */
        $ipType = Type::build('ip');
        $object = new \stdClass();
        $object->_encode = function ($value) {
            $object = new TestClass();
            return $object->encode($value);
        };
        $object->_decode = function ($value) {
            $object = new TestClass();
            return $object->decode($value);
        };
        $ipType->_encode = $object->_encode;
        $ipType->_decode = $object->_decode;

        $ips = $this->Ips->find('all', ['conditions' => ['type' => 'callable']])->all()->toArray();
        $this->assertCount(2, $ips);
        $this->assertTextEquals('127.0.0.11', $ips[0]->ip);
        $this->assertTextEquals('::11', $ips[1]->ip);

        $ip = $this->Ips->newEntity(['ip' => '127.0.0.2']);
        $this->Ips->save($ip);
        $this->Ips->schema()->columnType('ip', 'string');

        $ip = $this->Ips->get($ip->id);
        $this->assertTextEquals(call_user_func($ipType->_encode, '127.0.0.2'), $ip->ip);
    }

    public function testMethodObjectStaticFunctionMethod()
    {
        /** @var IpType $ipType */
        $ipType = Type::build('ip');
        $ipType->_encode = 'TestApp\Lib\TestClass::staticEncode';
        $ipType->_decode = 'TestApp\Lib\TestClass::staticDecode';

        $ips = $this->Ips->find('all', ['conditions' => ['type' => 'callable']])->all()->toArray();
        $this->assertCount(2, $ips);
        $this->assertTextEquals('127.0.0.11', $ips[0]->ip);
        $this->assertTextEquals('::11', $ips[1]->ip);

        $ip = $this->Ips->newEntity(['ip' => '127.0.0.2']);
        $this->Ips->save($ip);
        $this->Ips->schema()->columnType('ip', 'string');

        $ip = $this->Ips->get($ip->id);
        $this->assertTextEquals(call_user_func($ipType->_encode, '127.0.0.2'), $ip->ip);
    }

    /**
     * testBadAssertNoRedirect
     *
     * @return void
     */
    public function testNotCallableMethod()
    {
        /** @var IpType $ipType */
        $ipType = Type::build('ip');
        $ipType->_encode = 'not_a_callable';
        $ipType->_decode = 'not_a_callable';

        try {
            $this->Ips->find()->all()->toArray();
        } catch (\Exception $e) {
            $this->assertTrue($e instanceof \InvalidArgumentException);
            return;
        }

        $this->assertTrue(false);
    }
}
