<?php

namespace Viaativa\Viaroot\Traits;

use Chumper\Zipper\Facades\Zipper;
use Illuminate\Http\File as FileUpload;
use Illuminate\Support\Facades\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Sabberworm\CSS\Parser;
use Sabberworm\CSS\OutputFormat;
use Sabberworm\CSS\Rule\Rule;
use Sabberworm\CSS\RuleSet\AtRuleSet;
use Sabberworm\CSS\Value\CSSFunction;
use Sabberworm\CSS\Value\CSSString;
use Sabberworm\CSS\Value\RuleValueList;
use Sabberworm\CSS\Value\URL;

class ImportIcons
{

    //This is for avoid wrong css upload.
    private $invalidCssNames = ['demo', 'test', 'example'];
    private $zip;
    private $name;

    public function __construct($name, UploadedFile $zipFile)
    {
        $this->name = Str::slug($name);
        $this->zip = Zipper::make($zipFile->getPathname());
    }

    function uploadIconsFiles()
    {
        $eot = $this->uploadIconFile('eot');
        $svg = $this->uploadIconFile('svg');
        $ttf = $this->uploadIconFile('ttf');
        $woff = $this->uploadIconFile('woff');
        return [
            "eot" => $eot,
            "svg" => $svg,
            "ttf" => $ttf,
            "woff" => $woff
        ];
    }

    private function uploadIconFile($extension)
    {
        $iconFilePaths = "";
        $iconFileName = "{$this->name}.{$extension}";

        $publicPath = public_path("icons\\{$this->name}");
        $filesPath = $this->zip->listFiles("/\.{$extension}$/");

        if (!is_dir($publicPath)) {
            $this->createIconFileDir($this->name);
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
            "icons\\{$this->name}"
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
        $filePath = "{$publicPath}\\{$iconFileName}";
        File::put($filePath, $fileContent);
        return $iconFileName;
    }


    function getCSSPath($zip)
    {
        $cssList = $zip->listFiles($this->generateGetCssRegex());
        if (sizeof($cssList)) {
            return $cssList[0];
        }
        return [];
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

    function getCSSFile()
    {
        $cssPath = $this->getCSSPath($this->zip);
        return $this->zip->getFileContent($cssPath);
    }

    function updateCSSFile($cssFile, $itensArr)
    {
        dump($itensArr);
        $oCss = (new Parser($cssFile))->parse();
        foreach ($oCss->getAllRuleSets() as $ruleSet) {
            if ($ruleSet instanceof AtRuleSet) {
                if ($ruleSet->atRuleName() == 'font-face') {
//                    $oCss->remove($ruleSet);
                }
            }
        }

        $this->createFontFace($itensArr);

        foreach ($oCss->getAllRuleSets() as $asd) {
            if ($asd instanceof AtRuleSet) {
                dump($asd);
            }
        }
//        foreach($oCss->getSelectorsBySpecificity() as $oRuleSet) {
//            dump($oRuleSet);
//            $oRuleSet->removeRule('font-family');
//        }

        foreach ($oCss->getAllDeclarationBlocks() as $oBlock) {
            foreach ($oBlock->getRules() as $oRules) {
                if ($oRules->getRule() == "font-family") {
                    $oRules->setValue($this->name);
                }
            }
        }

        dd($oCss->render(OutputFormat::create()->indentWithSpaces(4)->setSpaceBetweenRules("\n")));
    }

    private function createFontFace($iconsFiles)
    {
        $fontFamily = new AtRuleSet('font-face');

        $ruleFontFamily = new Rule('font-family');
        $ruleFontFamily->setValue(new CSSString($this->name));

        $ruleSrcNormal = new Rule('src');
        $ruleSrcNormal->setValue(new URL(new CSSString($iconsFiles['eot'])));

        $ruleSrcFixes = new Rule('src');
        $ruleSrcFixes->addValue(new URL(new CSSString($iconsFiles['eot']."#iefix")));
        $ruleSrcFixes->addValue($this->addValueListFontType('embedded-opentype', $iconsFiles['ttf']));
        $ruleSrcFixes->addValue($this->addValueListFontType('truetype', $iconsFiles['woff']));
        $ruleSrcFixes->addValue($this->addValueListFontType('woff', $iconsFiles['svg']));

        $ruleFontWeight = new Rule('font-weight');
        $ruleFontWeight->setValue('normal');

        $ruleFontStyle = new Rule('font-style');
        $ruleFontStyle->setValue('normal');

        $fontFamily->addRule($ruleFontFamily);
        $fontFamily->addRule($ruleSrcNormal);
        $fontFamily->addRule($ruleSrcFixes);
        $fontFamily->addRule($ruleFontWeight);
        $fontFamily->addRule($ruleFontStyle);
        dump($fontFamily);
    }

    private function addValueListFontType($functionString, $iconsFile){
        $ruleValueList = new RuleValueList();
        $ruleValueList->addListComponent(new CSSFunction('format', new CSSString($functionString), ','));
        $ruleValueList->addListComponent(new URL(new CSSString($iconsFile)));
        return $ruleValueList;
    }
}
