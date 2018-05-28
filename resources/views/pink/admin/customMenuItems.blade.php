@foreach($items as $item)

    <tr>
        <td style="text-align: left;">{{ $paddingLeft }} {!! Html::link(route('adminMenus.edit',['menus' => $item->id]),$item->title) !!}</td>
        <td>{{ $item->url() }}</td>

        <td>
            {!! Form::open(['url' => route('adminMenus.destroy',['menu'=> $item->id]),'class'=>'form-horizontal','method'=>'POST']) !!}
            {{ method_field('DELETE') }}
            {!! Form::button('Удалить', ['class' => 'btn btn-french-5','type'=>'submit']) !!}
            {!! Form::close() !!}

        </td>
    </tr>
    @if($item->hasChildren())

        @include(env('THEME').'.admin.customMenuItems', array('items' => $item->children(),'paddingLeft' => $paddingLeft.'--'))

    @endif

@endforeach