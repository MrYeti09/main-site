<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataType;
use Viaativa\Viaroot\Models\MenuItem;
use Viaativa\Viaroot\Traits\DatabaseDataType;
use Viaativa\Viaroot\Traits\DatabaseDataRow;
use Viaativa\Viaroot\Traits\DatabaseDataRowRelationship;
use Viaativa\Viaroot\Traits\DatabaseMenus;
use Viaativa\Viaroot\Traits\DatabasePermissions;

class DefaultData extends Seeder
{
    use DatabaseDataType;
    use DatabaseDataRow, DatabaseDataRowRelationship;
    use DatabaseMenus;
    use DatabasePermissions;

    public function run()
    {




        $this->addDataType(
            'fonts',
            'fonts',
            'Fonte',
            'Fontes',
            'Viaativa\\Viaroot\\Models\\Fonts'
        );

        $this->createPermissions([
            'fonts',
        ]);

        MenuItem::where('menu_id', '1')->delete();

        $this->defineDataType('fonts');
        $this->addDataRow('id', 'number', 'ID', 0, 0, 0, 0, 0, 0);
        $this->addDataRow('font_name', 'text', 'Nome', 0);
        $this->addDataRow('font_family', 'text', 'Font Family', 0);
        $this->addDataRow('font_weights', 'text', 'Pesos', 0);

        if (DataType::where('slug', 'page-categories')->exists() == false) {
            $this->addDataType(
                'page-categories',
                'page-categories',
                'Categoria da Página',
                'Categorias de Páginas',
                'Viaativa\\Viaroot\\Models\\PageCategory'
            );
            $this->createPermissions([
                'page-categories',
            ]);

        }


        $this->defineDataType('page-categories');
        $this->addDataRow('id', 'number', 'ID', 0, 0, 0, 0, 0, 0);
        $this->addDataRow('order', 'text', 'Ordem', 0, 0, 0, 0, 0, 0);
        $this->addDataRow('name', 'text', 'Nome', 0,1,1,1,1,1,'',"['type' => 'main']");
        $this->addDataRow('icon', 'text', 'Icone', 0, 0, 0, 0, 0, 0);
        $this->addDataRow('slug', 'text', 'Slug', 0, 1, 1, 1, 1, 1);
        $this->addDataRow('parent_id', 'select_dropdown', 'Categoria', 0, 1, 1, 1, 1, 1, json_encode((object)["link" => [
            "model" => 'Viaativa\Viaroot\Models\PageCategory',
            "display" => 'name'
        ]]));

        $this->addDataRow('image', 'media_picker', 'Imagem', 0);

//

//        $this->useAdminAsParentMenu();
//        $this->addMenuItem('Modulos', 'voyager-browser',"","",null,5,json_encode([1]));
//        $main = MenuItem::where('title','Modulos')->first();

        $this->useAdminAsParentMenu();
        $this->addMenuItem('Painel', 'voyager-boat', "voyager.dashboard", "", null, 5, json_encode([1]));
        $this->addMenuItem('Mídia', 'voyager-images', "voyager.media.index", "", null, 5, json_encode([1]));
        $this->addMenuItem('Páginas', 'voyager-file-text', "voyager.page-categories.index", null, "", 5, json_encode([1]));
//        $this->addChildMenuItem('Categorias', 'voyager-file-text',"voyager.page-categories.index");
//        $this->addChildMenuItem('Todas', 'voyager-news',"voyager.pages.index");
        $this->addMenuItem('Blog', 'voyager-news', null, "", null, 5, json_encode([1]));
        $this->addChildMenuItem('Posts', 'voyager-news', "voyager.blog_posts.index");
        $this->addChildMenuItem('Categorias', 'voyager-categories', "voyager.categories.index");

        $this->useAdminAsParentMenu();
        $this->addMenuItem('Ferramentas', 'voyager-tools', null, "", null, 5, json_encode([1]));
        $this->addChildMenuItem('Menu Builder', 'voyager-list', "voyager.menus.index");
        $this->addChildMenuItem('Database', 'voyager-data', "voyager.database.index");
        $this->addChildMenuItem('Compass', 'voyager-compass', "voyager.compass.index");
        $this->addChildMenuItem('BREAD', 'voyager-bread', "voyager.bread.index");

        $this->useAdminAsParentMenu();
        $this->addMenuItem('Configurações', 'voyager-settings', null, "", null, 5, json_encode([1]));
        $this->addChildMenuItem('Config. Avançadas', 'voyager-settings', "voyager.settings.index");
        $this->addChildMenuItem('Formulários', 'voyager-documentation', "voyager.forms.index");
        $this->addChildMenuItem('Header/Footer', 'voyager-params', "voyager.page-blocks.main-settings");
        $this->addChildMenuItem('Usuários', 'voyager-person', "voyager.users.index");
        $this->addChildMenuItem('Permissões', 'voyager-lock', "voyager.roles.index");
        $this->addChildMenuItem('Google Fontes', 'voyager-character', 'voyager.fonts.index');

        $items = DataType::whereNotIn('name', ["", "icons", "page_categories", "page-categories", "enquiries", "inputs", "forms", "settings_formfields", 'users', 'menus', 'roles', 'categories', 'posts', 'pages', 'blog_posts', 'page_blocks', 'fonts'])->get();
        $this->useAdminAsParentMenu();
        $this->addMenuItem('Modulos', 'voyager-browser', 'no-route', '', '#2bb7d6', 5, json_encode([1]));
        foreach ($items as $item) {
            if (isset($item->icon) and strlen($item->icon)) {
                $icon = $item->icon;
            } else {
                $icon = 'voyager-browser';
            }
            $this->addChildMenuItem($item->display_name_plural, $icon, "voyager." . str_replace("_", "-", $item->slug) . ".index");
        }

        $this->useAdminAsParentMenu();
        $this->addMenuItem('Desenvolvedor', 'voyager-lightbulb', 'no-route', '', '#ffc66d', 5, json_encode([1]));
        $this->addChildMenuItem('Documentação', 'voyager-study', "docs-dev");
        $this->addChildMenuItem('Verificador', 'voyager-laptop', "verify-admin");



        DataType::where('slug','page')->update(['controller' => '\Viaativa\Viaroot\Http\Controllers\PageViaController','model_name' => 'Viaativa\Viaroot\Models\Page']);
        DataType::where('slug','page-blocks')->update(['controller' => '\Viaativa\Viaroot\Http\Controllers\PageBlockController','model_name' => 'Viaativa\Viaroot\Models\PageBlock']);
        DataType::where('slug','page-categories')->update(['controller' => '\Viaativa\Viaroot\Http\Controllers\PageCategoryController','model_name' => 'Viaativa\Viaroot\Models\PageCategory']);
        DataType::where('slug','enquiries')->update(['controller' => '\Viaativa\Viaroot\Http\Controllers\EnquiryController','model_name' => 'Viaativa\Viaroot\Models\Enquiry']);
        DataType::where('slug','forms')->update(['controller' => '\Viaativa\Viaroot\Http\Controllers\FormController','model_name' => 'Viaativa\Viaroot\Models\Form']);
        DataType::where('slug','inputs')->update(['controller' => '\Viaativa\Viaroot\Http\Controllers\InputController']);
//        DataType::where('slug','page')->update(['controller' => '\Viaativa\Viaroot\Http\Controllers\PageViaController','model' => 'Viaativa\Viaroot\Models\Page']);
//        DataType::where('slug','page')->update(['controller' => '\Viaativa\Viaroot\Http\Controllers\PageViaController','model' => 'Viaativa\Viaroot\Models\Page']);

    }

}
