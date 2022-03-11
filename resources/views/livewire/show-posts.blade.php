<div wire:init="loadPosts">

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2">
        <!-- This example requires Tailwind CSS v2.0+ -->
        <x-table>

            <div class="px-6 py-4 flex items-center">
                    <div class="flex items-center">
                        <span>Mostrar</span>
                        <select wire:model="cant" class="mx-2 form-control">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>

                        <span>Entradas</span>
                    </div>
                    <x-jet-input class="flex-1 mx-4 py-1 " placeholder="Escriba lo que va a buscar" type="text"
                    wire:model="search" />

                @livewire('create-post')
            </div>

            @if (count($posts))
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="w-1/4 overflow-hidden cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" wire:click="order('id')">
                                Id

                                                                {{-- sort --}}
                                                                @if ($sort == 'id')

                                                                @if ($direction == 'asc')
                                                                    <i class="fas fa-sort-alpha-up-alt float-right  mt-1"></i></th>
                                                                @else
                                                                    <i class="fas fa-sort-alpha-down-alt float-right  mt-1"></i></th>
                                                                @endif


                                                            @else
                                                                <i class="fas fa-sort float-right  mt-1"></i></th>
                                                            @endif

                            </th>
                            <th scope="col"
                                class="w-1/4 overflow-hidden cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" wire:click="order('title')">
                                Título

                                {{-- sort --}}
                                @if ($sort == 'title')

                                    @if ($direction == 'asc')
                                        <i class="fas fa-sort-alpha-up-alt float-right  mt-1"></i></th>
                                    @else
                                        <i class="fas fa-sort-alpha-down-alt float-right  mt-1"></i></th>
                                    @endif


                                @else
                                    <i class="fas fa-sort float-right  mt-1"></i></th>
                                @endif


                            <th scope="col"
                                class="w-1/4 overflow-hidden cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" wire:click="order('content')">
                                Contenido
                                                            {{-- sort --}}
                                                            @if ($sort == 'content')

                                                            @if ($direction == 'asc')
                                                                <i class="fas fa-sort-alpha-up-alt float-right  mt-1"></i></th>
                                                            @else
                                                                <i class="fas fa-sort-alpha-down-alt float-right  mt-1"></i></th>
                                                            @endif


                                                        @else
                                                            <i class="fas fa-sort float-right  mt-1"></i></th>
                                                        @endif

                            </th>
                            <th scope="col" class=" w-1/4 overflow-hidden  px-6 py-3">
                                <span class="sr-only">Acciones</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($posts as $item)
                            <tr>

                                <td class="px-6 py-4 ">
                                    <div class="text-sm text-gray-900">{{ $item->id }}</div>
                                </td>
                                <td class="px-6 py-4 ">
                                    <div class="text-sm text-gray-900">{{ $item->title }}</div>
                                </td>
                                <td class="px-6 py-4 ">
                                    <div class="text-sm text-gray-900">
                                        {!! $item->content !!}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium flex">
                                    {{-- @livewire('edit-item', ['item' => $item], key($item->id)) --}}
                                    <a class="btn btn-green" wire:click="edit({{ $item }})">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <a class="btn btn-red ml-2" wire:click="$emit('deletePost', {{ $item->id }})">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach


                        <!-- More people... -->
                    </tbody>
                </table>

                @if ($posts->hasPages())
                    <div class="px-6 py-3">
                        {{ $posts ->links()}}
                    </div>
                @endif

            @else
                <div class="py-6 px-4">
                    No hay resultados
                </div>
            @endif


        </x-table>

    </div>

    <x-jet-dialog-modal wire:model="open_edit">
        <x-slot name="title">
            Editar el post
        </x-slot>

        <x-slot name="content">

            <div wire:loading wire:target="image" class="mb-4 w-full bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Imagen cargando!</strong>
                <span class="block sm:inline">Espere un momento.</span>
              </div>

            @if ($image)
                <img class="mb-4" src={{ $image->temporaryUrl() }}>
            @elseif ($post->image)
              <img src={{ Storage::url($post->image) }} alt="">
            @endif

            <div class="mb-4">
                <x-jet-label value="Título del post" />
                <x-jet-input wire:model="post.title" type="text" class="w-full" />
            </div>

            <div>
                <x-jet-label value="Contenido del post" />
                <textarea wire:model="post.content" rows="6" class="form-control w-full" ></textarea>
            </div>

            <div>
                <input type="file" wire:model="image" id="{{ $identificador }}">
                <x-jet-input-error for="image" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button class="mr-4" wire:click="$set('open_edit', false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-danger-button class="mr-4 disabled:opacity-25" wire:click="update" wire:loading.attr="disabled">
                Actualizar
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>

    @push('js')
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Livewire.on('deletePost', postId => {
                    Swal.fire({
                    title: 'Estas seguro?',
                    text: "No puedes desacer el cambio!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'SI, borrar post!'
                    }).then((result) => {
                    if (result.isConfirmed) {

                        Livewire.emitTo('show-posts', 'delete', postId);

                        Swal.fire(
                        'Eliminado!',
                        'Su post se ha borrado.',
                        'éxito'
                        )
                    }
                })
            });
        </script>
    @endpush

</div>
