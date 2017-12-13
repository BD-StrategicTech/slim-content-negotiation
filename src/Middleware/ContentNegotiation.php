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

abstract class ContentNegotiation
{
    /**
     * Content Negotiation Invokable class
     *
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @param Callable $next
     * @return \Slim\Http\Response
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        $acceptHeader = $request->getHeader('ACCEPT');
        foreach ($acceptHeader as $accept) {
            if (!in_array($accept, $this->acceptedContentTypes)) {
                return $response->withStatus(406);
            }
        }

        $contentTypeHeaders = $request->getHeader('CONTENT_TYPE');
        foreach ($contentTypeHeaders as $content) {
            if (!in_array($content, $this->acceptedContentTypes)) {
                return $response->withStatus(415);
            }
        }
        return $next($request, $response);
    }
}
