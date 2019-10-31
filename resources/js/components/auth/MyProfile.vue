<template>
<div class="profile">
    <div class="profile-banner">
        <div class="profile-banner-header">
            <div class="name-avatar">
                <div>
                    <!-- <img v-if="image != ''" :src="image" alt=""> -->
                    <img :src="getAvatar()" alt="">
                </div>
                <h2>{{ user.name }} {{ user.surname }}</h2>
            </div>
        </div>
    </div>
    
    <div class="profile-info-edit" v-if="userRoles[0].name =='Administrator'">
        
        <label for="">Name:</label>
        <input type="text" name="" id="" v-model="name">
        <label v-if="errors.name" for=""></label><span class="errors" v-if="errors.name"><i class="fa fa-warning"></i>{{errors.name[0]}}</span>

        <label for="">Surame:</label>            
        <input type="text" name="" id="" v-model="surname">
        <label v-if="errors.surname" for=""></label ><span class="errors" v-if="errors.surname"><i class="fa fa-warning"></i>{{errors.surname[0]}}</span>

        <label for="">Jmbg:</label>            
        <input type="text" name="" id="" v-model="jmbg">
        <label v-if="errors.jmbg" for=""></label ><span class="errors" v-if="errors.jmbg"><i class="fa fa-warning"></i>{{errors.jmbg[0]}}</span>

        <label for="">Personal ID:</label>            
        <input type="text" name="" id="" v-model="jmbg">
        <label v-if="errors.p_id" for=""></label ><span class="errors" v-if="errors.p_id"><i class="fa fa-warning"></i>{{errors.p_id[0]}}</span>


        <label for="">E-mail:</label>
        <input type="text" v-model="email">
        <label  v-if="errors.email" for=""></label><span class="errors" v-if="errors.email"><i class="fa fa-warning"></i>{{errors.email[0]}}</span>


        <label>Role:</label>
        <label for="">{{user.roles[0].name}}</label>      

        <label for=""></label><button class="red-modal-button" @click="update">Update</button>
    </div>
    <div class="profile-info-edit" v-else>
        <div>
            <h4>My info:</h4>
        </div>
        <label for="">Name:</label>
        <label>{{name}}</label>

        <label for="">Surame:</label>            
        <label>{{surname}}</label>

        <label for="">E-mail:</label>
        <label>{{email}}</label>

        <label for="" v-if ="jmbg">Jmbg:</label>
        <label  v-if ="jmbg">{{jmbg}}</label>

        <label  v-if ="p_id" for="">Personal id:</label>
        <label  v-if ="jmbg">{{p_id}}</label>

        <label for="">Role:</label>
        <label>{{roleName}}</label>
        
        <label for="">Type:</label>
        <label for="">{{typeName}}</label>
    </div>
    <div class="tab-container">
        <div class="tabs">
            <a v-for="tab in tabs"  :class="[ activeTabName === tab.name ? 'active' : '' ]"  @click="setActiveTabName(tab.name)">{{tab.displayName}}</a>
        </div>
        <div class="tab-content">
            <UserVisits v-if="displayContents('visits')"  :userId="JSON.stringify(user.id)" />
            <UserPlannedVisits :userId="JSON.stringify(user.id)" v-if="displayContents('planned')"/>
            <UserMeetings v-if="displayContents('meetings')" :user="user"/>
            <NdaContainer :userId="JSON.stringify(user.id)" v-if="displayContents('nda')"/>
        </div>
    </div>
    <div class="profile-info-edit">
        <label for="">Password:</label>
        <input type="password" v-model="password">
        <label for=""></label><button class="red-modal-button" @click="updatePassword">Change password</button>
    </div>
    <div class="profile-info-edit">
        <label for="">Change photo</label>
        <input type="file"  @change="imageChanged" name="" id ="">
        <label for=""></label><button class="red-modal-button" @click="updateImage">Upload Photo</button>
    </div>
</div>
</template>
<script>
import UserVisits from '../profile_tabs/UserVisits'
import UserMeetings from '../profile_tabs/UserMeetings'
import UserPlannedVisits from '../profile_tabs/UserPlannedVisits'
import NdaContainer from '../profile_tabs/NdaContainer'

export default {
    name: 'my-profile',
    components: {
        UserVisits,
        UserMeetings,UserPlannedVisits,
        NdaContainer
    },
    created() {
        this.$store.dispatch('fetchAuthUser'),
        this.$store.dispatch('fetchAllRoles')
    },
    data() {
        return {
            countedMeetings: '',
            name: '',
            surname: '',
            typeName: '',
            email: '',
            password: '',
            image: '',
            jmbg: '',
            p_id: '',
            roleName: '',
            coffies: 0,
            errors: [],

            tabs: [
                {
                    name: 'visits',
                    displayName: 'Visits'
                },
                {
                    name: 'meetings',
                    displayName: 'Meetings'
                },
                {
                    name: 'planned',
                    displayName: 'Planned Visits'
                },
                {
                    name: 'nda',
                    displayName: 'Contracts'
                }
            ],
            activeTabName: 'visits',
        }
    },
    mounted() {
        this.getOneUser()
        this.getCountedMeetings()
    },
    computed: {
        user() {
            return this.$store.state.user
        },
        roles() {
            return this.$store.getters.getAllRoles
        },
        userRoles() {
            return this.$store.state.user_roles
        },
        token() {
            return this.$store.state.token
        }
    },
    methods: {
        getOneUser() {
            axios.get('/api/user/' + this.user.id + '?token=' + this.token )
                .then(response => {
                    this.name = response.data.name,
                    this.surname = response.data.surname,
                    this.email = response.data.email
                    if (typeof response.data.roles != null || typeof response.data.roles != undefined) {
                        this.roleName = response.data.roles[0].name
                        this.roleId = response.data.roles[0].id
                    }
                    this.jmbg = response.data.jmbg
                    this.p_id = response.data.p_id
                    this.typeName = response.data.type.name
                    this.coffies = response.data.coffies
                })
                .catch(error => {
                    console.log(error)
                })
        },
        getAvatar() {
            if(this.user.image !== null) {
                return "/images/" + this.user.sub_folder + '/' + this.user.image
            }
            if(this.user.image === null) {
                return "https://www.w3schools.com/howto/img_avatar.png"
            }
        },
        imageChanged(e) {
            console.log(e.target.files[0])
            let fileReader = new FileReader()
            fileReader.readAsDataURL(e.target.files[0])
            fileReader.onload = (e) => {
                this.image = e.target.result
            }
        },
        setRole(event) {
            const value = event.target.value
            console.log(value)
        },
        update() {
            this.$store.dispatch('updateUser', {
                id: this.user.id,
                name: this.name,
                surname: this.surname,
                email: this.email,
                p_id: this.p_id,
                jmbg: this.jmbg
            })
            .then(response =>{
                this.errors = []
                this.getOneUser()
            })
            .catch(error => {
                this.errors = error.response.data.errors
            })
        },
        updatePassword() {
            this.$store.dispatch('updateUserPassword',{
                id: this.user.id,
                password: this.password
            })
            .then(res => {
                this.getOneUser()
                this.password = ''
            })
        }, 
        updateImage() {
            this.loading = true
            this.$store.dispatch('updateUserProfileImage',{
                id: this.user.id,
                image: this.image
            })
            .then(response => {
                this.getOneUser()
                this.loading = false
                this.image = ''
            })
            .catch(error => {
                this.errors = error.response.data.errors
            })
        },
        setActiveTabName(name) {
            this.activeTabName = name;
        },
        displayContents(name) {
            return this.activeTabName === name;
        },
        getCountedMeetings() {
            axios.get('/api/meetings/user/'+ this.user.id +'/count?token=' + this.token)
                .then (response => {
                    this.countedMeetings = response.data
                })
        },
    }
}
</script>


