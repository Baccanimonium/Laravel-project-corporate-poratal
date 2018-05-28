<div id="content-page" class="content group">
    <div class="hentry group">
        <h3 class="title_page">Пользователи</h3>


        <div class="short-table white">
            <table style="width: 100%" cellspacing="0" cellpadding="0">
                <thead>

                <th>Name</th>
                <th>Link</th>

                <th>Удалить</th>
                </thead>
                @if($menu)

                    @include(env('THEME').'.admin.customMenuItems', array('items' => $menu->roots(),'paddingLeft' => ''))


                @endif
            </table>
        </div>
        {!! HTML::link(route('adminMenus.create'),'Добавить  пункт',['class' => 'btn btn-the-salmon-dance-3']) !!}

    </div>
</div>