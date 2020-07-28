<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.11/vue.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div id="app">

            <!-- Modal -->
            <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Todo List Form</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="">Content</label>
                                <textarea v-model='content' class="form-control" name="" id="" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" v-on:click="saveTodoList" class="btn btn-primary">Save
                                Todolist</button>
                            {{-- v-on:click dapat disingkat menjadi @click --}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-3"></div>
                <div class="col-sm-6">

                    <div class="text-right mb-3">
                        <a href="javascript:;" v-on:click="openForm" class="btn btn-primary">Tambah Todolist</a>
                    </div>

                    <div class="text-center mb-3">
                        <input type="text" v-model="search" placeholder="Cari data..." @change="findData"
                            class="form-control">
                        {{-- @change merupakan singkatan dari v-on:change --}}
                    </div>

                    <div class="todolist-wrapper">
                        <table class="table table-striped table-bordered">
                            <tbody>
                                <tr v-for='item in data_list'>
                                    <td>@{{ item.content }}
                                        <a href="javascript:;" @click="editData(item.id)"
                                            class='btn btn-primary'>Edit</a>
                                        <a href="javascript:;" @click="deleteData(item.id)"
                                            class='btn btn-danger'>Delete</a>
                                    </td>
                                    {{-- @ untuk memberi tahu bahwa penggunaan tanda {{  }}
                                    diatas adalah untuk vue bukan untuk blade --}}
                                </tr>
                                <tr v-if='!data_list.length'>
                                    <td>Data Masih Kosong</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-sm-3"></div>
            </div>
        </div>
    </div>

    <script>
        const app = new Vue({
            el: '#app',
            mounted() {
                this.getDataList();
            },
            data: {
                data_list: [],
                content: '',
                id: '',
                search: ''
            },
            methods: {
                findData: function(){
                    this.getDataList();
                },
                openForm: function () {
                    $('#modal-form').modal('show');
                },
                deleteData: function(id) {
                    if (confirm('Yakin ingin menghapus data?')) {
                        axios.delete(("{{ url('api/todolist/delete') }}/"+id))
                        .then(res => {
                            alert(res.data.message);
                            this.getDataList();
                        })
                        .catch(err => {
                            alert(err);
                        })
                    }
                },
                editData: function(id) {
                    this.id = id;

                    axios.get("{{ url('api/todolist/read') }}/"+this.id)
                    .then(res => {
                        const item = res.data;
                        this.content = item.content;

                        $('#modal-form').modal('show');
                    })
                    .catch(err => {
                        alert(err);
                    })
                },
                saveTodoList: function () {
                    const form_data = new FormData();
                    form_data.append('content', this.content);

                    if (this.id) {
                        axios.put("{{ url('/api/todolist/update') }}/"+this.id,{
                            content: this.content
                        })
                        .then(res => {
                            this.getDataList();
                        })
                        .catch(err => {
                            alert(err);
                        })
                        .finally(()=>{
                            $('#modal-form').modal('hide');
                        })
                    }else{
                        axios.post("{{ url('/api/todolist/create') }}",form_data)
                        .then(res => {
                            this.getDataList();
                        })
                        .catch(err => {
                            alert(err);
                        })
                        .finally(()=>{
                            $('#modal-form').modal('hide');
                        })
                    }
                },
                getDataList: function () {
                    axios.get("{{ url('api/todolist/list') }}?search="+this.search)
                    .then(res => {
                        this.data_list = res.data
                    })
                    .catch(err => {
                        alert(err);
                    })
                }
            },
        })
    </script>
</body>

</html>
