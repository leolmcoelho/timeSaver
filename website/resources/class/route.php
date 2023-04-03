<?php

namespace ModulePHP;

class Route
{
    public $path;
    public $lite = false;
    public $content;
    public $type;
    public $userlevel;

    function __construct()
    {

        $this->path = isset($_SERVER["REQUEST_URI"]) ? explode("?", substr($_SERVER["REQUEST_URI"], 1))[0] : "";
        $this->userlevel = USERLEVEL;
        $this->typeconfig();
    }

    function typeconfig()
    {
        $path_array = explode("/", $this->path);
        switch ($path_array[0]) {
            case 'controller':
                header('Content-type: application/json; charset=utf-8');
                array_shift($path_array);

                

                $this->path = implode("/", $path_array);
                $this->type =  "controller";
                $this->path =  "app/$this->userlevel/controller/$this->path.php";
                break;

            case 'assets':
                switch ($path_array[1]) {
                    case 'css':
                        header("Content-type: text/css");
                        break;

                    case 'js':
                        header("Content-type: application/javascript");
                        break;

                    default:
                        header("Content-type:" . @mime_content_type("../public_html/$this->path"));
                        break;
                }
                $this->type =  "assets";
                $this->path =  "public_html/$this->path";
                break;

            default:
                $this->type =  "page";
                header("Content-type: text/html; charset=utf-8");
                if (!$this->path) {
                    $this->path = "index";
                }

                $this->path =  "app/$this->userlevel/view/pages/$this->path.php";
          
                break;
        }
    }

    function lite()
    {
        $path_array = explode("/", $this->path);
        if (array_reverse($path_array)[0] == "lite") {
            array_pop($path_array);
            $this->lite = true;
        } else {
            $this->lite = false;
        }
        $this->path = implode("/", $path_array);
    }

    function liteaction()
    {
    }

    function open()
    {
        global $M;
        $message = $M->Config->message;
        if (!stream_resolve_include_path($this->path)) {
            $this->path = preg_replace("/$this->userlevel/", "global", $this->path, 1);
            if ($this->type == "page") {
                if (!stream_resolve_include_path($this->path)) {
                    if ($this->path == "app/global/view/pages/logout.php") {
                        User::logout();
                    }
                    $this->path = "app/$this->userlevel/view/pages/404.php";
                    if (!stream_resolve_include_path($this->path)) {
                        $this->path = "app/$this->userlevel/view/pages/404.php";
                        if (!stream_resolve_include_path($this->path)) {
                            $this->path = "app/global/view/pages/404.php";
                            if (!stream_resolve_include_path($this->path)) {
                                $this->content = "<h1>$message->PAGE_NOT_FOUND</h1>";
                                return $this->content;
                            }
                        }
                    }
                }
            } else {
                $this->content = MVC::JsonResponse(array(
                    'success' => false,
                    'status' => $message->FILE_NOT_FOUND
                ), false);
            }
        }

        ob_start();
        
        require_once($this->path);
        $this->content = ob_get_contents();
        ob_end_clean();
        return $this->content;
    }
}