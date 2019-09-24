<?php

namespace Viaativa\Viaroot\Classes;

use Chumper\Zipper\Facades\Zipper;
use Illuminate\Support\Facades\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Sabberworm\CSS\CSSList\AtRuleBlockList;
use Sabberworm\CSS\OutputFormat;
use Sabberworm\CSS\Parser;
use Sabberworm\CSS\Property\Selector;
use Sabberworm\CSS\Rule\Rule;
use Sabberworm\CSS\RuleSet\AtRuleSet;
use Sabberworm\CSS\RuleSet\DeclarationBlock;
use Sabberworm\CSS\Value\CSSFunction;
use Sabberworm\CSS\Value\CSSString;
use Sabberworm\CSS\Value\RuleValueList;
use Sabberworm\CSS\Value\Size;
use Sabberworm\CSS\Value\URL;
use Viaativa\Viaroot\Models\Icon;

class ImportIcons
{

    private $invalidCssNames = ['demo', 'test', 'example'];
    private $zip;
    private $name;
    private $slug;
    private $items = [];
    private $cssFiles = [];
    private $fontFamilyName = "";
    public $icons = [];

    public function __construct($name, UploadedFile $zipFile)
    {
        $this->name = $name;
        $this->slug = "icon-".Str::random(12);
        $this->zip = Zipper::make($zipFile->getPathname());
    }


    function uploadIconsFiles()
    {
        $eot = $this->uploadIconFile('eot');
        $svg = $this->uploadIconFile('svg');
        $ttf = $this->uploadIconFile('ttf');
        $woff = $this->uploadIconFile('woff');
        $this->items = [
            "eot" => $eot,
            "svg" => $svg,
            "ttf" => $ttf,
            "woff" => $woff
        ];
    }

    private function uploadIconFile($extension)
    {
        $iconFilePaths = "";
        $iconFileName = "{$this->slug}.{$extension}";

        $publicPath = public_path("icons/{$this->slug}");
        $filesPath = $this->zip->listFiles("/\.{$extension}$/");

        if (!is_dir($publicPath)) {
            $this->createIconFileDir();
        }
        if (sizeof($filesPath)) {
            $iconFilePaths = $this->putFile($filesPath[0], $publicPath, $iconFileName);
        }
        return $iconFilePaths;
    }

    private function createIconFileDir()
    {
        $paths = [
            "icons",
            "icons/{$this->slug}"
        ];

        foreach ($paths as $path) {
            if (!is_dir($path)) {
                mkdir($path);
            }
        }
    }

    private function putFile($filePath, $publicPath, $iconFileName)
    {
        $fileContent = $this->zip->getFileContent($filePath);
        $filePath = "{$publicPath}//{$iconFileName}";
        File::put($filePath, $fileContent);
        return $iconFileName;
    }


    function getCSSPath($zip)
    {
        $cssList = $zip->listFiles($this->generateGetCssRegex());
        if (sizeof($cssList)) {
            return $cssList[0];
        }
        return "";
    }

    private function generateGetCssRegex()
    {

        $regex = '/';
        foreach ($this->invalidCssNames as $invalidCssName) {
            $regex .= "(^(?!.*{$invalidCssName}(|s)).*)";
        }
        $regex .= "(\.css)$/i";
        return $regex;
    }


    function setCSSFile()
    {
        $cssPath = $this->getCSSPath($this->zip);
        if (strlen($cssPath)) {
            $this->cssFiles = $this->zip->getFileContent($cssPath);
        }
    }

    function updateCSSFile()
    {
        $oCss = (new Parser($this->cssFiles))->parse();
        foreach ($oCss->getContents() as $content) {
            if ($content instanceof AtRuleBlockList) {
                $oCss->remove($content);
            }
            if ($content instanceof AtRuleSet) {
                if ($content->atRuleName() == 'font-face') {
                    $oCss->remove($content);
                }
            }
            if ($content instanceof DeclarationBlock) {
                foreach ($content->getSelectors() as $index => $selector) {
                    if (preg_match('/(^\[class(\^|\*)(|\ )\=(|\ )\").*((\"\])(|\:after|\:before)$)/', $selector)) {
                        $this->fontFamilyName = preg_replace([
                            '/\ /', '/(^\[class(\^|\*)\=\")/', '/((\"\])(|\:after|\:before)$)/',
                        ], '', $selector);
                        $oCss->remove($content);
                        break;
                    }

                    if (preg_match('/(^\.).*(\:after$|\:before$)/i', $selector->getSelector())) {
                        $selector->setSelector(str_replace($this->fontFamilyName, $this->slug . "-", $selector->getSelector()));
                        $this->icons[] = str_replace([":after",":before"], '', substr($selector->getSelector(), 1));
                        break;
                    }
                }
                foreach ($content->getRules() as $oRules) {
                    if ($oRules->getRule() == "font-family") {
                        $oRules->setValue("{$this->slug}");
                    }
                }
            }
        }

        $oCss->prepend($this->createClassSelector());
        $oCss->prepend($this->createMediaQuery());
        $oCss->prepend($this->createFontFace($this->items));

        $this->saveCSSFile($oCss);
    }

    private function createMediaQuery()
    {
        $blockList = new AtRuleBlockList('media', 'screen and (-webkit-min-device-pixel-ratio:0)');
        $fontFace = new AtRuleSet('font-face');

        $fontFamily = new Rule('font-family');
        $fontFamily->setValue(new CSSString($this->slug));

        $src = new Rule('src');
        $src->addValue(new URL( new CSSString("./{$this->slug}.svg#{$this->slug}")));
        $src->addValue(new CSSFunction('format', [new CSSString('svg')], ','));

        $fontFace->addRule($fontFamily);
        $fontFace->addRule($src);

        $blockList->append($fontFace);
        return $blockList;
    }

    private function createClassSelector()
    {
        $classSelector = new DeclarationBlock();
        $classSelector->setSelectors([
            new Selector("[class^=\"{$this->slug}-\"]:before"),
            new Selector("[class*=\" {$this->slug}-\"]:before"),
            new Selector("[class^=\"{$this->slug}-\"]:after"),
            new Selector("[class*=\" {$this->slug}-\"]:after"),
        ]);

        $fontFamily = new Rule('font-family');
        $fontFamily->setValue($this->slug);

        $fontStyle = new Rule('font-style');
        $fontStyle->setValue('normal');

        $classSelector->addRule($fontFamily);
        $classSelector->addRule($fontStyle);
        return $classSelector;
    }

    private function createFontFace($iconsFiles)
    {
        $fontFamily = new AtRuleSet('font-face');

        $ruleFontFamily = new Rule('font-family');
        $ruleFontFamily->setValue(new CSSString($this->slug));

        $ruleSrcNormal = new Rule('src');
        $ruleSrcNormal->setValue(new URL(new CSSString("./{$iconsFiles['eot']}")));

        $ruleSrcFixes = new Rule('src');
        $ruleSrcFixes->addValue(new URL(new CSSString("./{$iconsFiles['eot']}#iefix")));
        $ruleSrcFixes->addValue($this->addValueListFontType('embedded-opentype', $iconsFiles['ttf']));
        $ruleSrcFixes->addValue($this->addValueListFontType('truetype', $iconsFiles['woff']));
        $ruleSrcFixes->addValue($this->addValueListFontType('woff', "{$iconsFiles['svg']}#$this->slug"));
        $ruleSrcFixes->addValue(new CSSFunction("format", new CSSString('svg'), ","));

        $ruleFontWeight = new Rule('font-weight');
        $ruleFontWeight->setValue('normal');

        $ruleFontStyle = new Rule('font-style');
        $ruleFontStyle->setValue('normal');

        $fontFamily->addRule($ruleFontFamily);
        $fontFamily->addRule($ruleSrcNormal);
        $fontFamily->addRule($ruleSrcFixes);
        $fontFamily->addRule($ruleFontWeight);
        $fontFamily->addRule($ruleFontStyle);

        return $fontFamily;
    }

    private function addValueListFontType($functionString, $iconsFile)
    {
        $ruleValueList = new RuleValueList();
        $ruleValueList->addListComponent(new CSSFunction('format', new CSSString($functionString), ','));
        $ruleValueList->addListComponent(new URL(new CSSString("./{$iconsFile}")));
        return $ruleValueList;
    }

    private function saveCSSFile($css)
    {

        $publicPath = public_path("icons/{$this->slug}");
        if (!is_dir($publicPath)) {
            $this->createIconFileDir();
        }
        $filePath = "{$publicPath}/{$this->slug}.css";
        File::put($filePath, $css->render());
    }


    function saveIcon()
    {
        $icon = new Icon();
        $icon->name = $this->name;
        $icon->slug = $this->slug;
        $icon->path = "icons/{$this->slug}/{$this->slug}.css";
        $icon->icons = $this->icons;
        $icon->save();
    }


    function import()
    {
        $this->uploadIconsFiles();
        $this->setCSSFile();
        $this->updateCSSFile();
        $this->saveIcon();
    }
}
