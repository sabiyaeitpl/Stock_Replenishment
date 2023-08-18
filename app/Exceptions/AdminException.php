<?php
 
namespace App\Exceptions;
 
use Exception;
 
class AdminException extends Exception
{
    
    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    { 
        return response()->view(
                'errors.admin-exception',
                array(
                    'exception' => $this
                )
        );
    }
}