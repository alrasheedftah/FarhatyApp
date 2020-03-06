<template>
    <div class="container">
        <div class="row mt-5" v-if="$gate.isAdminOrAuther()">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Offers Table</h3>

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
                      <th>Offer Name</th>
                      <th>Offer Price</th>
                      <th>Professor</th>
                        <th>Created</th>
                      <th>Modify</th>
                    </tr>
                  </thead>
                  <tbody>
                  <tr v-for="user in offers.data " :key="user.id">
                  <td>{{user.id}}</td>
                  <td>{{user.offer_name}}</td>
                  <td>{{user.offer_price}}</td>
                  <td><span class="tag tag-success">{{user.professor_id }}</span></td>
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
                    <pagination :data="offers"
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
        <h5 class="modal-title"  v-show="!editeMode" id="exampleModalLabel">Add Offer</h5>
          <h5 class="modal-title" v-show="editeMode" id="updateModaleTitle">Update Offer Info</h5>


          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <form @submit.prevent="editeMode ? updateUser() :createUser()">
      <div class="modal-body">
        <div class="form-group">
        <input v-model="form.offer_name" type="text" name="offer_name"  placeholder="Offer Name "
               class="form-control" :class="{'is-invalid':form.errors.has('offer_name')}" >
            <has-error :form="form" field="offer_name"></has-error>

        </div>

          <div class="form-group">
              <input v-model="form.offer_price" type="number" name="offer_price"  placeholder="Offers Prices"
                     class="form-control" :class="{'is-invalid':form.errors.has('offer_price')}" >
              <has-error :form="form" field="offer_price"></has-error>

          </div>


          <div class="form-group">
              <select v-model="form.professor_id" name="professor_id"  placeholder="Choose Specific Professor " id="professor_id"
                        class="form-control" :class="{'is-invalid':form.errors.has('professor_id')}" >
              <option value="">Select Professore for Offer</option>
                  <option v-for="option in professors.data" :value="option.id" >{{option.first_name+""+option.last_name}}</option>
              </select>
              <has-error :form="form" field="professor_id"></has-error>

          </div>


          <div class="form-group">
              <input v-model="form.offer_start_time" type="time" name="offer_start_time"  placeholder="Choose Begin Time your Offer"
                     class="form-control" :class="{'is-invalid':form.errors.has('offer_start_time')}" >
              <has-error :form="form" field="offer_start_time"></has-error>

          </div>

          <div class="form-group">
              <input v-model="form.offer_end_time" type="time" name="offer_end_time"  placeholder="Choose End Time your Offer"
                     class="form-control" :class="{'is-invalid':form.errors.has('offer_end_time')}" >
              <has-error :form="form" field="offer_end_time"></has-error>

          </div>


                    <div class="form-group">
                        <textarea v-model="form.offer_descripe" name="offer_descripe"  placeholder="Short Description for Offer (Opetiona) " id="bio"
                                  class="form-control" :class="{'is-invalid':form.errors.has('offer_descripe')}" ></textarea>
                        <has-error :form="form" field="offer_descripe"></has-error>

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
                offers:{},
                professors:{},
                form :new Form({
                    id:'',
                    offer_name:'',
                    offer_start_time:'',
                    offer_end_time:'',
                    offer_price:'',
                    professor_id:'',

                })
            }
        },

        methods:{

            updateModal(userData){
                this.getProfessor()
                this.editeMode=true
                $('#addNewUserModal').modal('show')
                this.form.reset()
                this.form.fill(userData)
            },

            newModal(){
                this.getProfessor()
                this.editeMode=false
                this.form.reset()
                $('#addNewUserModal').modal('show')

            },

            loadUsers(){
                if(this.$gate.isAdminOrAuther()){
                axios.get('api/offers')
                .then(({data})=>{
                    this.offers=data
                })}
            },

            updateUser(id){
                this.$Progress.start()
              this.form.put('api/offers/'+this.form.id)
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
                        this.form.delete('api/offers/'+id).then(()=>{
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
              axios.get('api/offers?page='+page)
              .then(response=>{
                  this.offers=response.data
              })
            },
          createUser(){
              this.$Progress.start();
              this.form.post('api/offers')
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

          },
            getProfessor(){
                if(this.$gate.isAdminOrAuther()){
                    axios.get('api/proffessors')
                        .then(({data})=>{
                            this.professors=data
                        })}
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
                    this.offers=data.data
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
