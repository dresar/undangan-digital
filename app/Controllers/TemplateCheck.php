<?php

namespace App\Controllers;

class TemplateCheck extends BaseController
{
    public function templates1()
    {
        $templateSlug = 'templates1';
        $templateBase = base_url('templates/' . $templateSlug . '/');

        $cssFiles = [
            'css/AJ6WMXU0ifQz.css',
            'css/XqNUbrSYylIr.css',
            'css/pikgDPHIyez4.css',
            'css/custom-styles.css',
            'css/xMrC6RWz6iDx.css',
            'css/NC0J9wkM3S0I.css',
            'css/4OpiVuEjwA1a.css',
        ];

        $jsFiles = [
            'js/ufQMK1QEjACp.js',
            'js/8NzGgkVjVT0a.js',
            'js/gSBOGSMk4YWb.js',
            'js/f9ZXBQqKnYuu.js',
            'js/9boPudOgnlI0.js',
            'js/xbIDkx0u8sJO.js',
            'js/vmwtYDstcY0g.js',
            'js/QJwAIZVXnt3r.js',
            'js/88TNzZS6VR0G.js',
            'js/7pJsznPqBaIc.js',
            'js/U0FKeXfidc9w.js',
            'js/BRVtaR4upiDk.js',
            'js/lc1KtScPhRgN.js',
            'js/vgmRjg7qf3B5.js',
            'js/J5LskQ57EavF.js',
            'js/kCmjJIWmVxkX.js',
            'js/iRgucg8A6fxZ.js',
            'js/kFEpAyjzZ2nC.js',
            'js/qxEZ5TKh3uOp.js',
            'js/A9EWesRRqBaW.js',
            'js/01ds4pxZA8cJ.js',
        ];

        return view('debug/template_check', [
            'template_base' => $templateBase,
            'css_files' => $cssFiles,
            'js_files' => $jsFiles,
        ]);
    }
}


