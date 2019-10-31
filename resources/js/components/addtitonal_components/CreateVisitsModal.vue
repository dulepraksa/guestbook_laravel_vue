<template>
    <div class="create-meeting-modal" v-if="modal == true">
        <div class="modal-wrapper">
            <div class="modal-container">
                <div class="modal-header">
                    <h3>Create Visit</h3>
                </div>
                <div class="modal-body">
                    <div v-if="chosenUser.image" class="modal-image">
                        <img :src="getImage()" alt="">
                    </div>
                    <div class="modal-search">
                        <label>Search:</label>
                        <input  type="text" name="" id="" v-model="query">
                        <span  class="errors" v-if="errors.user_id"> <i class="fa fa-warning"></i>You have to choose a user</span>
                        <div class="search-results" v-if="users.length > 0">
                            <div v-for="user in users" :key="user.id" type="text" placeholder="Search...">
                                <a @click="choose(user.id, user.name, user.surname, user)" type="checkbox" :id="user.id">
                                    <div>
                                            {{ user.name }} {{user.surname}}
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="modal-checkbox">
                        <input type="checkbox" v-model="is_planned" name="checkbox" id="checkbox">
                        <label for="checkbox">Check to make planned visit</label>
                    </div>
                    <div v-if="is_planned" class="modal-item">
                        <label>Planned time:</label>
                        <input type="datetime-local" v-model="planned_time">
                        <span class="errors" v-if="errors.planned_time"><i class="fa fa-warning"></i>Planned time is required</span>
                    </div>
                    <div v-if="!is_planned" class="modal-item">
                        <label>Arrived at:</label>
                        <input v-model="arrived_at" type="datetime-local" name="" id="">
                        <span class="errors" v-if="(errors.arrived_at)&&(!is_planned)"><i class="fa fa-warning"></i>Time of arrival is required</span>
                    </div>
                    <div v-if="!is_planned" class="modal-item">
                        <label>Left at:</label>
                        <input v-model="left_at" type="datetime-local" name="" id="">
                        <span class="errors" v-if="errors.left_at"><i class="fa fa-warning"></i>{{errors.left_at[0]}}</span>
                    </div>
                    <div class="modal-item">
                        <label>Description:</label>
                        <input v-model="description" type="text" name="" id="">
                        <span class="errors" v-if="errors.description"><i class="fa fa-warning"></i>{{errors.description[0]}}</span>
                    </div>

                    <div class="modal-footer">
                        <button class="red-modal-button" @click="createVisit">Submit</button>
                        <button class="red-modal-button" @click="close">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        modal: Boolean
    },
    data() {
        return {
            users: [],
            image: '',
            is_planned: false,
            query: null,
            arrived_at: '',
            left_at: '',
            planned_time: '',
            userId: '',
            userName:'',
            userSurname: '',
            description: '',
            errors: [],
            chosenUser: {}
        }
    },
    watch: {
        query(after, before) {
            this.fetchUsers()
        }
    },
    computed: {
        token() {
            return this.$store.state.token
        },
        authUser() {
            return this.$store.state.user
        },
        authUserRole() {
            return this.$store.state.user_roles[0].name
        }
    },
    methods: {
        close() {
            this.$emit('close-visit-modal')
            this.errors = []
            this.query = null
            this.userName = ''
            this.userSurname = ''
            this.is_planned = false
            this.planned_time = ''
            this.userId = ''
            this.arrived_at = ''
            this.left_at = ''
            this.description = '',
            this.image = ''
        },
        choose(uid, uname, usurname, user) {
            console.log( uid, uname, usurname)
            this.query = uname + ' ' + usurname
            this.userId = uid
            this.userName = uname
            this.userSurname = usurname
            this.chosenUser = user
        },
        fetchUsers() {
            axios.get('/api/searchUsers?q=' + this.query, {
                headers: {
                    'Authorization': 'Bearer ' + this.token
                }
            })
                .then(response => this.users = response.data)
                .then(response => console.log(response))
                .catch(error => {console.log(error)});
        },
        imageChanged(e) {
            console.log(e.target.files[0])
            let fileReader = new FileReader()
            fileReader.readAsDataURL(e.target.files[0])
            fileReader.onload = (e) => {
                this.image = e.target.result
            }
        },
        createVisit() {
            this.$store.dispatch('addVisit',{
                id: this.userId,
                description: this.description,
                arrived_at: this.arrived_at,
                planned_time: this.planned_time,
                planned: this.is_planned,
                image: this.image,
                left_at: this.left_at
            })
            .then(response => {
                this.errors = []
                this.query = null
                this.userName = ''
                this.userSurname = ''
                this.is_planned = false
                this.planned_time = ''
                this.userId = ''
                this.arrived_at = ''
                this.left_at = ''
                this.description = '',
                this.image = ''
                console.log(this.errors)
                if(this.errors.length == 0) {
                    this.close()
                }
            })
            .catch( error => {
                this.errors = error.response.data.errors
                this.userId = null
                this.query = null
            })
        },
        getImage() {
            return "/images/" + this.chosenUser.sub_folder + '/' + this.chosenUser.image
        }
    }
}
</script>
