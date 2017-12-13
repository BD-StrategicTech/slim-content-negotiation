<?php
/**
 * @author Matt Frost <mattf@budgetdumpster.com>
 * @package BudgetDumpster
 * @subpackage Middleware
 * @copyright Budget Dumpster, LLC 2017
 */
namespace BudgetDumpster\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

class ContentNegotiationHalJson extends ContentNegotiation
{
    /**
     * @var Array
     */
    protected $acceptedContentTypes = ['application/json', 'application/hal+json'];
}
