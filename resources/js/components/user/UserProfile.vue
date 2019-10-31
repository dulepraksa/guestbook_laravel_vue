<template>
<div class="profile">
    <div v-if="loading" class="loader-center">
        <loader></loader>
    </div>
    <div v-if="!loading" div class="profile-banner">
        <div class="profile-banner-header">
            <div class="name-avatar">
                <div>
                    <img :src="getAvatar()" alt="">
                </div>
                <h2>{{ dname }} {{ dsurname }}</h2>
            </div>
        </div>
    </div>
    <div v-if="!loading && userRoles[0].name =='Administrator'" class="profile-info-edit">
        <label for="">Name:</label>
        <input type="text" v-model="name">
        <label v-if="errors.name" for=""></label><span class="errors" v-if="errors.name"><i class="fa fa-warning"></i>{{errors.name[0]}}</span>


        <label for="">Surame:</label>            
        <input type="text" name="" id="" v-model="surname">
        <label v-if="errors.surname" for=""></label ><span class="errors" v-if="errors.surname"><i class="fa fa-warning"></i>{{errors.surname[0]}}</span>

        <label v-if="userPr.jmbg" for="">Jmbg:</label>            
        <input v-if="userPr.jmbg" type="text" name="" id="" v-model="jmbg">
        <label v-if="errors.jmbg && userPr.jmbg" for=""></label ><span class="errors" v-if="errors.jmbg"><i class="fa fa-warning"></i>{{errors.jmbg[0]}}</span>

        <label v-if="userPr.p_id" for="">Personal ID:</label>            
        <input v-if="userPr.p_id" type="text" name="" id="" v-model="jmbg">
        <label v-if="errors.p_id && userPr.p_id" for=""></label ><span class="errors" v-if="errors.p_id"><i class="fa fa-warning"></i>{{errors.p_id[0]}}</span>

        <label for="">E-mail:</label>
        <input type="text" v-model="email">
        <label  v-if="errors.email" for=""></label><span class="errors" v-if="errors.email"><i class="fa fa-warning"></i>{{errors.email[0]}}</span>


        <label for="">Assign Role:</label>            
        <select name="" id="" @change="setRole($event)">
            <option v-for="role in roles" :selected="role.id == userPr.roles[0].id" :value="role.id" :key="role.id">{{ role.name }}</option>
        </select>

        <label for="">Assign Type:</label>            
        <select name="" id="" @change="setType($event)">
            <option disabled value="">Please select one</option>
            <option v-for="type in types" :value="type.id" :selected="type.id == userPr.type.id" :key="type.id"> {{ type.name }}</option>
        </select>

        <label for=""></label><button class="red-modal-button" v-if="user.roles[0].name == 'Administrator'" @click="updateUser">Update</button>
    </div>
    <div v-if="!loading && userRoles[0].name != 'Administrator'" class="profile-info-edit">
        <div>
            <h4>User info:</h4>
        </div>
        <label for="">Name:</label>
        <label>{{userPr.name}}</label>

        <label for="">Surame:</label>            
        <label>{{userPr.surname}}</label>

        <label for="">E-mail:</label>
        <label>{{userPr.email}}</label>

        <label v-if="userPr.jmbg" for="">Jmbg:</label>
        <label v-if="userPr.jmbg">{{userPr.jmbg}}</label>

        <label v-if="userPr.p_id" for="">Personal id:</label>
        <label v-if="userPr.p_id">{{userPr.p_id}}</label>

        <label for="">User role:</label>
        <label>{{userPr.roles[0].name}}</label>

        <label for="">User type:</label>            
        <label for="">{{userPr.type.name}}</label>
    </div>
    <div v-if="!loading" class="tab-container">
        <div class="tabs">
            <a v-for="tab in tabs"  :class="[ activeTabName === tab.name ? 'active' : '' ]"  @click="setActiveTabName(tab.name)">{{tab.displayName}}</a>
        </div>
        <div class="tab-content">
            <UserVisits v-if="displayContents('visits')"  :userId="id" />
            <UserPlannedVisits :userId="id" v-if="displayContents('planned')"/>
            <UserMeetings v-if="displayContents('meetings')" :user="userPr"/>
            <NdaContainer :userId="id" v-if="displayContents('nda')"/>
        </div>
    </div>
    <div  class="profile-info-edit" v-if="!loading && userRoles[0].name =='Administrator'">
        <label for="">Password:</label>
        <input type="password" v-model="password">
        <label for=""></label>
        <button @click="updatePassword" class="red-modal-button">Change Password</button>
    </div>
    <div v-if="!loading && (userRoles[0].name =='Administrator' || userRoles[0].name =='Secretary' )" class="profile-info-edit">
        <label for="">Change photo</label>
        <input type="file"  @change="imageChanged" name="" id="">
        <label for=""></label><button class="red-modal-button" @click="updateImage">Upload Photo</button>
    </div>
    <div v-if="!loading && userRoles[0].name =='Administrator'" class="admin-actions">
        <button class="fas fa-trash button-red" @click="deleteUser()"></button>
    </div>
</div>
</template>
<script>
import Loader from '../addtitonal_components/Loader'
import UserVisits from '../profile_tabs/UserVisits'
import UserMeetings from '../profile_tabs/UserMeetings'
import UserPlannedVisits from '../profile_tabs/UserPlannedVisits'
import NdaContainer from '../profile_tabs/NdaContainer'

export default {
    name: 'user-profile',
    components: {
        Loader,UserVisits,
        UserMeetings,UserPlannedVisits,
        NdaContainer
    },
    props: {
        id: String
    },
    data() {
        return {
            dname: '',
            dsurname: '',
            name: '',
            surname: '',
            email: '',
            jmbg: '',
            p_id: '',
            password: '',
            user_role: '',
            loading: false,
            roleName: '',
            errors: [],
            image: '',
            sub_folder: '',
            updatedImage: '',
            typeId: '',
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
            meetings: [],
            visits: [],
            types: []
        }
    },
    created() {
        this.$store.dispatch('fetchAllRoles')
    },
    mounted() {
        this.getSingleUser()
        this.fetchTypes()
        this.getAvatar()
        console.log(this.typeId)
    },
    computed: {
        user() {
            return this.$store.state.user
        },
        userRoles() {
            return this.$store.state.user_roles
        },
        userPr() {
            return this.$store.getters.getUserProfile
        },
        roles() {
            return this.$store.getters.getAllRoles
        },
        token() {
            return this.$store.state.token
        }
    },
    methods : {
        getSingleUser() {
            this.loading = true
            this.$store.dispatch('getSingleUser', this.id)
            .then(response => {
                this.dname = response.data.name,
                this.dsurname = response.data.surname
                this.name = response.data.name,
                this.surname = response.data.surname,
                this.email = response.data.email
                this.image = response.data.image
                this.roleName = response.data.roles[0].name
                this.roleId = response.data.roles[0].id
                this.loading = false
                this.typeId = response.data.type_id
                this.visits = response.data.visits
                this.meetings = response.data.meetings
                console.log(response)
            })
            .catch(error => {
                this.loading = false
                console.log(error)
            })
        },
        getAvatar() {
            if(this.userPr.image === null) {
                return "https://www.w3schools.com/howto/img_avatar.png"
            }
            if(this.userPr.image !== null ) {
                return "/images/" + this.userPr.sub_folder + '/' + this.userPr.image
            }
        },
        imageChanged(e) {
            console.log(e.target.files[0])
                let fileReader = new FileReader()
                fileReader.readAsDataURL(e.target.files[0])
                fileReader.onload = (e) => {
                    this.updatedImage = e.target.result
                }
        },
        updateUser() {
            this.loading = true
            this.$store.dispatch('updateUser', {
                id: this.id,
                name: this.name,
                surname: this.surname,
                p_id: this.p_id,
                jmbg: this.jmbg,
                email: this.email,
                role: this.roleName,
                type_id: this.typeId,
            })
            .then(response => {
                console.log(this.roleName)
                this.getSingleUser()
                this.loading = false
            })
            .catch(error => {
                this.loading = false
                this.errors = error.response.data.errors
            })
        },
        updateImage() {
            // this.loading = true
            this.$store.dispatch('updateUserProfileImage',{
                id: this.id,
                image: this.updatedImage
            })
            .then(response => {
                this.updatedImage = ''
                this.getSingleUser()
            })
        },
        deleteUser() {
            this.$store.dispatch('deleteUser', this.id)
                .then(() =>{
                    this.$router.push('/dashboard/users')
                })
                .catch(error => {
                    console.log(error)
                })
        },
        setRole(event) {
            const value = Number(event.target.value)
            console.log(value)
            switch(value) {
                case 1:
                    this.roleName = 'Visitor'
                    console.log(this.roleName)
                break;
                case 2:
                    this.roleName = 'Staff'
                    console.log(this.roleName)
                break;
                case 3:
                    this.roleName = 'Secretary'
                    console.log(this.roleName)

                break;
                case 4:
                    this.roleName = 'GDPR-Admin'
                    console.log(this.roleName)

                break;
                case 5:
                    this.roleName = 'Administrator'
                    console.log(this.roleName)
                break;
                default:
                    this.roleName = this.userPr.roles[0].name
            }
        },
        setType(event) {
            const value = Number(event.target.value)
            console.log(value)
                this.typeId = value
                console.log(this.typeId)
        },
        updatePassword() {
            this.$store.dispatch('updateUserPassword',{
                id: this.user.id,
                password: this.password
            })
            .then(res => {
                this.getSingleUser()
                this.password = ''
            })
        },
        setActiveTabName(name) {
            this.activeTabName = name;
        },
        displayContents(name) {
            return this.activeTabName === name;
        },
        fetchTypes() {
            axios.get('/api/types?token=' +this.token) 
            .then(response => {
                this.types = response.data
            })
            .catch(error => {
                this.errors = error.response.data.errors
            })
        },
    }
}
</script>

