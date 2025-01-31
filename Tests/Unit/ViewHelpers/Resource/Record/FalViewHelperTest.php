<?php
namespace FluidTYPO3\Vhs\Tests\Unit\ViewHelpers\Resource\Record;

/*
 * This file is part of the FluidTYPO3/Vhs project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use FluidTYPO3\Vhs\Tests\Unit\ViewHelpers\AbstractViewHelperTest;
use FluidTYPO3\Vhs\Tests\Unit\ViewHelpers\AbstractViewHelperTestCase;
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\Resource\ResourceFactory;

/**
 * Class FalViewHelperTest
 */
class FalViewHelperTest extends AbstractViewHelperTestCase
{
    protected function setUp(): void
    {
        $this->singletonInstances[ResourceFactory::class] = $this->getMockBuilder(ResourceFactory::class)->disableOriginalConstructor()->getMock();
        $this->singletonInstances[FileRepository::class] = $this->getMockBuilder(FileRepository::class)->disableOriginalConstructor()->getMock();

        parent::setUp();
    }

    /**
     * @test
     */
    public function testFalViewhHelperWorkspaceHandling()
    {
        $this->markTestSkipped('Test skipped pending refactoring to Doctrine QueryBuilder');

        $GLOBALS['TYPO3_DB'] = $this->getMockBuilder(DatabaseConnection::class)->getMock();
        $GLOBALS['TYPO3_DB']->expects($this->once())
            ->method('exec_SELECTgetRows')
            ->with(
                'uid',
                'sys_file_reference',
                'tablenames=' .
                ' AND uid_foreign=0' .
                ' AND fieldname='
                . 'AND sys_file_reference.deleted=0 AND (sys_file_reference.t3ver_wsid=0 OR sys_file_reference.t3ver_wsid=1234) AND sys_file_reference.pid<>-1',
                '',
                'sorting_foreign',
                '',
                'uid'
            )
            ->will($this->returnValue(['foo']));
        $viewHelper = $this->createInstance();
        $viewHelperNode = $this->createViewHelperNode($viewHelper, []);
        $GLOBALS['BE_USER']->workspaceRec['uid'] = 1234;
        $this->executeViewHelper(['table' => 'pages', 'field' => 'media'], [], $viewHelperNode);
    }

    /**
     * @test
     */
    public function testFalViewhHelperWithoutWorkspaces()
    {
        $this->markTestSkipped('Test skipped pending refactoring to Doctrine QueryBuilder');

        $viewHelper = $this->createInstance();
        $viewHelperNode = $this->createViewHelperNode($viewHelper, []);
        $this->executeViewHelper(['table' => 'pages', 'field' => 'media'], [], $viewHelperNode);
    }
}
