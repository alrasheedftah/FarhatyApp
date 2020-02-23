<template>
    <div class="container">
        <div class="row mt-5" v-if="$gate.isAdminOrAuther()">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">proffessors Table</h3>

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
                      <th>Full Name</th>
                        <th>Nickname</th>
                        <th>address</th>
<!--                      <th>E-mail</th>-->
                      <th>gender</th>
                        <th>Beginning Work</th>
                        <th>Working Time</th>
                        <th>Phone Number</th>
                        <th>Status</th>
                        <th>Created</th>
                      <th>Modify</th>
                    </tr>
                  </thead>
                  <tbody>
                  <tr v-for="user in proffessors.data " :key="user.id">
                  <td>{{user.id}}</td>
                  <td>{{user.first_name +' '+user.last_name}}</td>
                      <td>{{user.nickname}}</td>
                  <td>{{user.address}}</td>
                      <td>{{user.gender}}</td>
                      <td>{{user.beginning_work}}</td>
                      <td>{{user.working_time}}</td>
                      <td>{{user.phone}}</td>
                  <td><span class="tag tag-success">{{user.status |upText}}</span></td>
                      <td>{{user.created_at | myDate}}</td>
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
                    <pagination :data="proffessors"
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
        <h5 class="modal-title"  v-show="!editeMode" id="exampleModalLabel">Add Proffessor</h5>
          <h5 class="modal-title" v-show="editeMode" id="updateModaleTitle">Update Proffessor Info</h5>


          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <form @submit.prevent="editeMode ? updateUser() :createUser()">
      <div class="modal-body">


          <div class="form-group">
              <select v-model="form.specialties_id" name="specialties_id"  placeholder="Choose Specialties Now" id="specialties_id"
                      class="form-control" :class="{'is-invalid':form.errors.has('specialties_id')}" >
                  <option value="">Select Specialty For Proff</option>
                  <option v-for="option in specialties.data" :value="option.id">{{option.special_name}}</option>
              </select>
              <has-error :form="form" field="gender"></has-error>

          </div>

        <div class="form-group">
        <input v-model="form.first_name" type="text" name="first_name"  placeholder="First Name "
               class="form-control" :class="{'is-invalid':form.errors.has('first_name')}" >
            <has-error :form="form" field="first_name"></has-error>

        </div>

          <div class="form-group">
              <input v-model="form.last_name" type="text" name="last_name"  placeholder="Last Name "
                     class="form-control" :class="{'is-invalid':form.errors.has('last_name')}" >
              <has-error :form="form" field="last_name"></has-error>

          </div>


          <div class="form-group">
              <input v-model="form.nickname" type="text" name="nickname"  placeholder="Nick Name "
                     class="form-control" :class="{'is-invalid':form.errors.has('nickname')}" >
              <has-error :form="form" field="nickname"></has-error>

          </div>


          <div class="form-group">
              <input v-model="form.beginning_work" type="text" name="beginning_work"  placeholder="Biginning Work "
                     class="form-control" :class="{'is-invalid':form.errors.has('beginning_work')}" >
              <has-error :form="form" field="beginning_work"></has-error>

          </div>


          <div class="form-group">
              <input v-model="form.working_time" type="text" name="working_time"  placeholder="First Name "
                     class="form-control" :class="{'is-invalid':form.errors.has('working_time')}" >
              <has-error :form="form" field="working_time"></has-error>

          </div>


          <div class="form-group">
              <input v-model="form.address" type="text" name="address"  placeholder="Place address "
                     class="form-control" :class="{'is-invalid':form.errors.has('address')}" >
              <has-error :form="form" field="address"></has-error>

          </div>

          <div class="form-group">
              <textarea v-model="form.description" name="description"  placeholder="Short Description for Proffessor (Opetional) " id="bio"
                        class="form-control" :class="{'is-invalid':form.errors.has('description')}" ></textarea>
              <has-error :form="form" field="description"></has-error>

          </div>

          <div class="form-group">
              <select v-model="form.status" name="status"  placeholder="Choose Avialable Now" id="status"
                        class="form-control" :class="{'is-invalid':form.errors.has('status')}" >
              <option value="">Select Proffessor Status</option>
                  <option value="AVIALABLE">Available</option>
                  <option value="UNAVIALABLE">Unavailable</option>
              </select>
              <has-error :form="form" field="status"></has-error>

          </div>



          <div class="form-group">
              <select v-model="form.gender" name="gender"  placeholder="Choose Avialable Now" id="gender"
                      class="form-control" :class="{'is-invalid':form.errors.has('gender')}" >
                  <option value="">Select Proffessor Status</option>
                  <option value="male">Male</option>
                  <option value="female">Female</option>
              </select>
              <has-error :form="form" field="gender"></has-error>

          </div>



          <div class="form-group">
              <input v-model="form.phone" type="text" name="phone"  placeholder="Your Phone"
                     class="form-control" :class="{'is-invalid':form.errors.has('phone')}" >
              <has-error :form="form" field="phone"></has-error>

          </div>

          <div class="form-group row">
              <label for="inputSkills" class="col-sm-2 col-form-label">Proffessors Photo </label>
              <div class="col-sm-10">
                  <input type="file" @change="readPhoto" name="image" class="form-control" id="inputSkills" placeholder="Photo">
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
                proffessors:{},
                specialties:{},
                form :new Form({
                    id:'',
                    first_name:'',
                    last_name:'',
                    address:'',
                    nickname:'',
                    description:'',
                    gender:'',
                    image:'',
                    beginning_work:'',
                    working_time:'',
                    status:'',
                    phone:'',
                    specialties_id:''
                })
            }
        },

        methods:{

            updateModal(userData){
                this.getSpecialties()
                this.editeMode=true
                $('#addNewUserModal').modal('show')
                this.form.reset()
                this.form.fill(userData)
            },

            newModal(){
                this.getSpecialties()
                this.editeMode=false
                this.form.reset()
                $('#addNewUserModal').modal('show')

            },

            loadUsers(){
                if(this.$gate.isAdminOrAuther()){
                axios.get('api/proffessors')
                .then(({data})=>{
                    this.proffessors=data
                })}
            },

            updateUser(id){
                this.$Progress.start()
              this.form.put('api/proffessors/'+this.form.id)
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
                        this.form.delete('api/proffessors/'+id).then(()=>{
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
              axios.get('api/proffessors?page='+page)
              .then(response=>{
                  this.proffessors=response.data
              })
            },
          createUser(){
              this.$Progress.start();
              this.form.post('api/proffessors')
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
            readPhoto(e){
                let file=e.target.files[0];
                console.log((file))
                var reader=new FileReader()
                if(file['size']<2111775) {
                    reader.onloadend = (file) => {
                        // console.log('Result ',reader.result)
                        this.form.image = reader.result
                    }
                }else{
                    Swal.fire('Failed','The Image Is More Than 10MB','warning')

                }

                reader.readAsDataURL(file)
            },

            getSpecialties(){
                if(this.$gate.isAdminOrAuther()){
                    axios.get('api/specialties')
                        .then(({data})=>{
                            this.specialties=data
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
                    this.proffessors=data.data
                })
                .catch(()=>{

                })
            })

            // setInterval(()=>this.loadUsers(),3000)
            //this.$Progress.finish();
        },
        // computed:{
        //     full_name:{
        //
        //     }
        // }
        //
        // mounted() {
        //     console.log('Component mounted.')
        // }
    }
</script>
