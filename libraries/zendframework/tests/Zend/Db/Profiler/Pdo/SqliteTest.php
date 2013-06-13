<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Db
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: SqliteTest.php 24593 2012-01-05 20:35:02Z matthew $
 */


/**
 * @see Zend_Db_Profiler_TestCommon
 */
require_once 'Zend/Db/Profiler/TestCommon.php';





/**
 * @category   Zend
 * @package    Zend_Db
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Db
 * @group      Zend_Db_Profiler
 */
class Zend_Db_Profiler_Pdo_SqliteTest extends Zend_Db_Profiler_TestCommon
{

    public function testProfilerPreparedStatementWithBoundParams()
    {
        $this->markTestIncomplete($this->getDriver() . ' is having trouble with binding params');
    }

    public function getDriver()
    {
        return 'Pdo_Sqlite';
    }
}
