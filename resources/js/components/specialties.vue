<template>
    <div class="container">
        <div class="row mt-5" v-if="$gate.isAdminOrAuther()">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Specialties Table</h3>

                <div class="card-tools">
                  <button class="btn btn-primary" data-toggle="modal" @click="newModal()">Add New <i class="fas fa-icons "></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Specialty Name</th>
                        <th>description</th>
                        <th>Proff-Count</th>
                      <th>Modify</th>
                    </tr>
                  </thead>
                  <tbody>
                  <tr v-for="specialty in specialties.data " :key="specialty.id">
                  <td>{{specialty.id}}</td>
                  <td>{{specialty.special_name}}</td>
                  <td>{{specialty.special_description}}</td>
                  <td><span class="tag tag-success">{{specialty.proffesors_total }}</span></td>

                      <td>
                  <a href="#" > <i class="fas fa-edit" @click="updateModal(specialty)"> </i>
                  </a>
                    /
                  <a href="#" @click="deleteSpecialty(specialty.id)"> <i class="fas fa-trash red"> </i>
                  </a>

                  </td>
                  </tr>


                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->

                <div class="card-footer">
                    <pagination :data="specialties"
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
        <h5 class="modal-title"  v-show="!editeMode" id="exampleModalLabel">Add Specialty</h5>
          <h5 class="modal-title" v-show="editeMode" id="updateModaleTitle">Update Specialty Info</h5>


          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <form @submit.prevent="editeMode ? updateSpecialties() :createSpecialty()">
      <div class="modal-body">
        <div class="form-group">
        <input v-model="form.special_name" type="text" name="special_name"  placeholder="Specialty Name"
               class="form-control" :class="{'is-invalid':form.errors.has('special_name')}" >
            <has-error :form="form" field="special_name"></has-error>

        </div>


          <div class="form-group">
              <textarea v-model="form.special_description" name="special_description"  placeholder="Short Description for Specialty (Opetiona) " id="bio"
                        class="form-control" :class="{'is-invalid':form.errors.has('special_description')}" ></textarea>
              <has-error :form="form" field="special_description"></has-error>

          </div>

          <div class="form-group row">
              <label for="inputSkills" class="col-sm-2 col-form-label">Icon Specialty</label>
              <div class="col-sm-10">
                  <input type="file" @change="readIcon" name="specialty_icon" class="form-control" id="inputSkills" placeholder="Skills">
              </div>
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
                specialties:{},
                form :new Form({
                    id:'',
                    special_name:'',
                    special_description:'',
                    special_icon:'',
                    proffesors_total:'',

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

            loadSpecialties(){
                if(this.$gate.isAdminOrAuther()){
                axios.get('api/specialties')
                .then(({data})=>{
                    this.specialties=data
                })}
            },

            updateSpecialties(id){
                this.$Progress.start()
              this.form.put('api/specialties/'+this.form.id)
                .then(()=>{
                    $('#addNewUserModal').modal('hide')
                    Swal.fire('Updated!','Updated Specialty Successfully ','success')
                    Fire.$emit('AfterCreated')
                    this.$Progress.finish()
                })
                .catch(()=>{
                    Swal.fire('Failed','There was Somthing Wrong ','warning')
                    this.$Progress.finish()
                })
            },

            deleteSpecialty(id){
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
                        this.form.delete('api/specialties/'+id).then(()=>{
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
              axios.get('api/specialties?page='+page)
              .then(response=>{
                  this.specialties=response.data
              })
            },
          createSpecialty(){
              this.$Progress.start();
              this.form.post('api/specialties')
              .then(()=>{

                  $('#addNewUserModal').modal('hide')

                  Toast.fire({
                      icon: 'success',
                      title: 'Created Specialty Successfully '
                  })

                  this.$Progress.finish();

                  Fire.$emit('AfterCreated');
              })
              .catch(()=>{

              })

          },
            readIcon(e){
                let file=e.target.files[0];
                console.log((file))
                var reader=new FileReader()
                if(file['size']<2111775) {
                    reader.onloadend = (file) => {
                        // console.log('Result ',reader.result)
                        this.form.specialty_icon = reader.result
                    }
                }else{
                    Swal.fire('Failed','The Image Is More Than 10MB','warning')

                }

                reader.readAsDataURL(file)
            },


        },

        created() {
            //this.$Progress.start();
            this.loadSpecialties()

            Fire.$on('AfterCreated',()=>{
                this.loadSpecialties()
            })

            Fire.$on("Searching",()=>{
                let query=this.$parent.search
                axios.get('api/findUser?q='+query)
                .then((data)=>{
                    this.specialties=data.data
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
