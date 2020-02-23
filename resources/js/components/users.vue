<template>
    <div class="container">
        <div class="row mt-5" v-if="$gate.isAdminOrAuther()">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Users Table</h3>

                <div class="card-tools">
                  <button class="btn btn-primary" data-toggle="modal" @click="newModal()">Add New <i class="fas fa-user-plus"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>User</th>
                      <th>E-mail</th>
                      <th>Type</th>
                        <th>Created</th>
                      <th>Modify</th>
                    </tr>
                  </thead>
                  <tbody>
                  <tr v-for="user in users.data " :key="user.id">
                  <td>{{user.id}}</td>
                  <td>{{user.name}}</td>
                  <td>{{user.email}}</td>
                  <td><span class="tag tag-success">{{user.type |upText}}</span></td>
                      <td>{{user.created | myDate}}</td>
                      <td>
                  <a href="#" > <i class="fas fa-edit" @click="updateModal(user)"> </i>
                  </a>
                    /
                  <a href="#" @click="deleteUser(user.id)"> <i class="fas fa-trash red"> </i>
                  </a>

                  </td>
                  </tr>


                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->

                <div class="card-footer">
                    <pagination :data="users"
                     @pagination-change-page="getResults" ></pagination>
                </div>

            </div>
            <!-- /.card -->
          </div>
        </div>

<div v-if="!$gate.isAdminOrAuther()">
    <not-found> </not-found>
</div>


<!-- Modal -->
<div class="modal fade" id="addNewUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"  v-show="!editeMode" id="exampleModalLabel">Add User</h5>
          <h5 class="modal-title" v-show="editeMode" id="updateModaleTitle">Update User Info</h5>


          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <form @submit.prevent="editeMode ? updateUser() :createUser()">
      <div class="modal-body">
        <div class="form-group">
        <input v-model="form.name" type="text" name="name"  placeholder="Name "
               class="form-control" :class="{'is-invalid':form.errors.has('name')}" >
            <has-error :form="form" field="name"></has-error>

        </div>

          <div class="form-group">
              <input v-model="form.email" type="text" name="email"  placeholder="E-mail address "
                     class="form-control" :class="{'is-invalid':form.errors.has('email')}" >
              <has-error :form="form" field="email"></has-error>

          </div>

          <div class="form-group">
              <textarea v-model="form.bio" name="bio"  placeholder="Short bio for user (Opetiona) " id="bio"
                        class="form-control" :class="{'is-invalid':form.errors.has('bio')}" ></textarea>
              <has-error :form="form" field="bio"></has-error>

          </div>

          <div class="form-group">
              <select v-model="form.type" name="type"  placeholder="Short bio for user (Opetiona) " id="type"
                        class="form-control" :class="{'is-invalid':form.errors.has('type')}" >
              <option value="">Select User Role</option>
                  <option value="admin">Admin</option>
                  <option value="user">User</option>
                  <option value="author">Auther</option>
              </select>
              <has-error :form="form" field="type"></has-error>

          </div>

          <div class="form-group">
              <input v-model="form.password" type="password" name="password"  placeholder="Your Passsword"
                     class="form-control" :class="{'is-invalid':form.errors.has('password')}" >
              <has-error :form="form" field="password"></has-error>

          </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button v-show="editeMode" type="submit" class="btn btn-primary">Update</button>
          <button v-show="!editeMode" type="submit" class="btn btn-primary">Create</button>
      </div>
        </form>

    </div>
  </div>
</div>

<!-- end Modal -->

    </div>
</template>

<script>
    export default {

        data(){
            return{
                editeMode:false,
                users:{},
                form :new Form({
                    id:'',
                    name:'',
                    email:'',
                    password:'',
                    type:'',
                    bio:'',
                    photo:''
                })
            }
        },

        methods:{

            updateModal(userData){
                this.editeMode=true
                $('#addNewUserModal').modal('show')
                this.form.reset()
                this.form.fill(userData)
            },

            newModal(){
                this.editeMode=false
                this.form.reset()
                $('#addNewUserModal').modal('show')

            },

            loadUsers(){
                if(this.$gate.isAdminOrAuther()){
                axios.get('api/user')
                .then(({data})=>{
                    this.users=data
                })}
            },

            updateUser(id){
                this.$Progress.start()
              this.form.put('api/user/'+this.form.id)
                .then(()=>{
                    $('#addNewUserModal').modal('hide')
                    Swal.fire('Updated!','Updated User Successfully ','success')
                    Fire.$emit('AfterCreated')
                    this.$Progress.finish()
                })
                .catch(()=>{
                    Swal.fire('Failed','There was Somthing Wrong ','warning')
                    this.$Progress.finish()
                })
            },

            deleteUser(id){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        // send ajax requestto delete
                        this.form.delete('api/user/'+id).then(()=>{
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )

                            Fire.$emit('AfterCreated')

                        })
                    }
                })
                .catch(()=>{
                    Swal.fire('Failed','There was Somthing Wrong ','warning')
                })

            },

            getResults(page=1){
              axios.get('api/user?page='+page)
              .then(response=>{
                  this.users=response.data
              })
            },
          createUser(){
              this.$Progress.start();
              this.form.post('api/user')
              .then(()=>{

                  $('#addNewUserModal').modal('hide')

                  Toast.fire({
                      icon: 'success',
                      title: 'Created User Successfully '
                  })

                  this.$Progress.finish();

                  Fire.$emit('AfterCreated');
              })
              .catch(()=>{

              })

          }
        },

        created() {
            //this.$Progress.start();
            this.loadUsers()

            Fire.$on('AfterCreated',()=>{
                this.loadUsers()
            })

            Fire.$on("Searching",()=>{
                let query=this.$parent.search
                axios.get('api/findUser?q='+query)
                .then((data)=>{
                    this.users=data.data
                })
                .catch(()=>{

                })
            })

            // setInterval(()=>this.loadUsers(),3000)
            //this.$Progress.finish();
        },
        //
        // mounted() {
        //     console.log('Component mounted.')
        // }
    }
</script>
