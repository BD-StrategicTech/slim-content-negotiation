<?php
/**
 * @author Matt Frost<mattf@budgetdumpster.com>
 * @package BudgetDumpster 
 * @subpackage Tests
 * @subpackage Helpers
 * @copyright BudgetDumpster LLC, 2017
 */
namespace BudgetDumpster\Tests\Helpers;

use Slim\Http\Request;
use Slim\Http\Response;

class ContentNegotiationCallable
{
    /**
     * A dummy invoke method
     */
    public function __invoke(Request $request, Response $response)
    {
        return $response;
    }
}
