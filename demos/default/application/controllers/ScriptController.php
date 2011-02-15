<?php
namespace Application;

use Glenn\Dispatchable,
    Glenn\Request,
    Glenn\Response;

class ScriptController implements Dispatchable
{
    /*
     * @return Response
     */
	public function dispatch(Request $request)
	{
        try {
            return new Response("Script executed");
        } catch (Exception $e) {
            return new Response("Internal server error", 500);
        }
	}
}