<?php

/* errors/error.html.twig */
class __TwigTemplate_6e56cc4cf51cf61960411dfba546d99e220fa80b648781984360bec04783955e extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<html>
<head>
    <title>";
        // line 3
        echo twig_escape_filter($this->env, (isset($context["message"]) ? $context["message"] : null), "html", null, true);
        echo "</title>
    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
    <style type=\"text/css\">
        *{
            font-family:\t\tConsolas, Courier New, Courier, monospace;
            font-size:\t\t\t14px;
        }
        body {
            background-color:\t#fff;
            margin:\t\t\t\t40px;
            color:\t\t\t\t#000;
        }

        #content  {
            border:\t\t\t\t#999 1px solid;
            background-color:\t#fff;
            padding:\t\t\t20px 20px 12px 20px;
            line-height:160%;
        }

        h1 {
            font-weight:\t\tnormal;
            font-size:\t\t\t24px;
            color:\t\t\t\t#990000;
            margin: \t\t\t0 0 4px 0;
        }
        pre {
            width: 100%;
            overflow-x: auto;
        }
    </style>
</head>
<body>
<div id=\"content\">
    <h1>Error: ";
        // line 37
        echo twig_escape_filter($this->env, (isset($context["message"]) ? $context["message"] : null), "html", null, true);
        echo "</h1>
    <pre>";
        // line 38
        echo twig_escape_filter($this->env, (isset($context["trace"]) ? $context["trace"] : null), "html", null, true);
        echo "</pre>
</div>
</body>
</html>";
    }

    public function getTemplateName()
    {
        return "errors/error.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  64 => 38,  60 => 37,  23 => 3,  19 => 1,);
    }
}
