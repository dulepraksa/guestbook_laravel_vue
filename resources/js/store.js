
import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
import * as notification from './modules/notification'
import * as visitNotification from './modules/visitNotification'
import * as meetingNotification from './modules/meetingNotification'
import VuexPersistence from 'vuex-persist';

Vue.use(Vuex)

const vuexPersist = new VuexPersistence({
    storage: window.localStorage,
    key: 'vuex',
})

export const store =  new Vuex.Store({
    plugins: [vuexPersist.plugin],
    modules: {
        notification,
        visitNotification,
        meetingNotification
    },
    state: {
        token: localStorage.getItem('token') || null,
        user: JSON.parse(localStorage.getItem('user')) || null,
        user_roles: localStorage.getItem('user_roles') || null,
        users:[],
        user_permissions: [],
        userMeta: {},
        userForProfile: {},
        user_visits: [],
        meetings: [],
        meeting: {},
        meetingsWithUsers: [],
        meetingsMeta: {},
        roles: [],
        permissions: [],
        allRoles: [],
        errors: [],
        guests: [],
        guest: {},
        guestMeetings: [],
        guestMeta: {},
        guestLinks: {},
        visits: [],
        myVisits: [],
        plannedVisits: [],
        visitsMeta: {},
        userVisitsMeta: {},
        guestVisitsPlannedMeta: {},
        visitsLinks: {},
        contracts:[],
        filter: '',
    },
    getters:{
        loggedIn(state) {
            return state.token != null
        },
        getUser(state) {
            return state.user
        },
        getUserProfile(state) {
            return state.userForProfile
        },
        getAllUsers(state) {
            return state.users
        },
        getUserMeta(state) {
            return state.userMeta
        },
        getAllRoles(state) {
            return state.allRoles
        },
        getGuests(state) {
            return state.guests
        },
        getOneGuest(state) {
            return state.guest
        },
        guestVists(state) {
            return state.myVisits
        },
        getErrors(state) {
            return state.errors
        },
        getActiveVisits(state) {
            return state.visits
        },
        getVisitMeta(state) {
            return state.visitsMeta
        },
        allMeetings(state) {
            return state.meetings
        },
        meetingsWithUsers(state) {
            return state.meetingsWithUsers
        },
        singleGuestMeetings(state) {
            return state.guestMeetings
        },
        getContracts(state) {
            return state.contracts
        },
        getMeetingsMeta(state) {
            return state.meetingsMeta
        },
        getUserVisitsMeta(state) {
            return state.userVisitsMeta
        },
        getMetaForGuestVisitsNotPlanned(state) {
            return state.getMetaForGuestVisitsNotPlanned
        }
    },
    mutations:{
        filterFilter(state, filter) {
            state.filter = filter
        },
        removeToken(state) {
            state.token = null
        },
        getToken(state,token) {
            state.token = token
        },
        getUser(state, user) {
            state.user = user
        },
        setUserForProfile(state, user) {
            state.userForProfile = user
        },
        pushUser(state, user) {
            state.users.push(user)
        },
        updateUser(state, user) {
            const index = state.users.findIndex(item => item.id == user.id)
            state.users.splice(index, 1, user)
        },
        updateUserImage(state, user) {
            console.log(user.id)
            const index =  state.users.findIndex(item => item.id == user.id)
            state.users[index].image = user.image
        },
        removeUserProfile(state, id) {
            const index = state.users.findIndex(item => item.id == id)
            state.users.splice(index, 1)
        },
        getAllUsers(state, users) {
            state.users = users
        },
        getUserRoles(state, roles) {
            state.user_roles = roles
        },
        setUserMeta(state, meta) {
            state.userMeta = meta
        },
        setAllRoles(state, roles) {
            state.allRoles = roles
        },
        retrieveGuests(state,guests) {
            state.guests = guests
        },
        setSingleGuest(state, guest) {
            state.guest = guest
        },
        deleteGuest(state, id) {
            const index = state.guests.findIndex(item => item.id == id)
            state.guests.splice(index, 1)
        },
        updateGuestImage(state, guest) {
            const index = state.guests.findIndex(item => item.id == guest.id)
            state.guests[index].avatar = guest.avatar
        },
        userVisits(state, visits) {
            state.myVisits = visits
        },
        getErrors(state,errors) {
            state.errors = errors
        },
        editGuest(state,guest) {
            state.guest = guest
        },
        getGuestMeta(state,meta) {
            state.guestMeta = meta
        },
        getGuestLinks(state,links) {
            state.GuestLinks = links
        },
        pushVisit(state, visit) {
            state.visits.push(visit)
        },
        removeVisit(state, id) {
            const index = state.visits.findIndex(item => item.id == id)
            state.visits.splice(index, 1)
        },
        plannedVisitsForUser(state,visits) {
            state.visits = visits
        },
        notPlannedVisitsForGuest(state, visits) {
            state.visits = visits
        },
        plannedVisitsForGuestMeta(state,meta) {
            state.guestVisitsPlannedMeta = meta
        },
        userVisitsMeta(state, meta) {
            state.userVisitsMeta = meta
        },
        updateVisit(state, visit) {
            const index = state.visits.findIndex(item => item.id == visit.id)
            state.visits.splice(index, 1, visit)
        },
        plannedVisits(state, visits) {
            state.visits = visits
        },
        plannedVisitsMeta(state, meta) {
            state.visitsMeta = meta
        },
        setFilteredVisits(state,visits) {
            state.visits = visits
        },
        activeVisitsMeta(state, meta) {
            state.visitsMeta = meta
        },
        todayVisits(state,visits) {
            state.visits = visits
        },
        todayVisitsMeta(state, meta) {
            state.visitsMeta = meta
        },
        yesterdayVisits(state,visits) {
            state.visits = visits
        },
        yesterdayVisitsMeta(state, meta) {
            state.visitsMeta = meta
        },
        thisWeekVisits(state,visits) {
            state.visits = visits
        },
        thisWeekVisitsMeta(state, meta) {
            state.visitsMeta = meta
        },
        lastWeekVisits(state,visits) {
            state.visits = visits
        },
        pushPlannedVisit(state, visit) {
            state.plannedVisits.push(visit)
        },
        removeMeta(state) {
            // state.visitsMeta = null
        },
        getMeetings(state, meetings) {
            state.meetings = meetings
        },
        setMeetingsForGuest(state, meetings) {
            state.guestMeetings = meetings
        },
        setMeetingsMeta(state, meta) {
            state.meetingsMeta = meta
        },
        pushMeeting(state, meeting) {
            state.meetings.push(meeting)
        },
        setMeetingsWithUsers(state, meetings) {
            state.meetingsWithUsers = meetings
        },
        updateMeeting(state, meeting) {
            const index = state.meetings.findIndex(item => item.id == meeting.id)
            // state.visits.splice(index, 1, meeting)
            Vue.set( state.meetings, index, meeting )
        },
        setContracts(state, contracts) {
            state.contracts = contracts
        },
        pushContract(state, contract) {
            state.contracts.push(contract)
        }
    },
    actions:{
        getToken({commit, dispatch},credentials) {
            return new Promise( (resolve,reject) => {
                axios.post('api/login', {
                    email: credentials.email,
                    password: credentials.password 
                })
                    .then(response => {
                        const token = response.data.token
                        const user  = response.data.user
                        const roles = response.data.user.roles
                            localStorage.setItem('token', token)
                            localStorage.setItem('user', JSON.stringify(user))
                            localStorage.setItem('user_roles', JSON.stringify(roles))
                            console.log(user)
                                commit('getToken',token)
                                commit('getUser',user)
                                commit('getUserRoles',roles)
                                    const notification = {
                                        type: 'success',
                                        message: 'Succesfully logged in!'
                                    }
                                    dispatch('notification/add', notification, { root: true })
                                        resolve(response)
                    })
                    .catch( error => {
                        // console.log(error)
                        reject(error)
                        const notification = {
                            type: 'error',
                            message: '' + error.response.data.error,
                        }
                        dispatch('notification/add', notification, { root: true })
                            throw error
                    })
            })
        },
        register(context, data) {
            return new Promise( (resolve,reject) => {
                axios.post('/api/register?token=' + this.state.token,{
                    name: data.name,
                    surname: data.surname,
                    email: data.email,
                    password: data.password,
                    image: data.image,
                    role: data.role,
                    jmbg: data.jmbg,
                    type_id: data.type_id
                })
                    .then(response => {
                        // context.commit('pushUser', response.data.user)
                        resolve(response)
                        const notification = {
                            type: 'error',
                            message: 'User created',
                        }
                        dispatch('notification/add', notification, { root: true })
                    })
                    .catch(error => {
                        const notification = {
                            type: 'error',
                            message: 'Invalid data' + error.message
                        }
                        reject(error)
                        dispatch('notification/add', notification, { root: true })
                        throw error
                    })
            })
        },
        logout(context) {
            return new Promise( (resolve,reject) => {
                axios.get('/api/logout?token=' + this.state.token)
                    .then(response => {
                        context.commit('removeToken')
                        localStorage.removeItem('token')
                        localStorage.removeItem('user')
                        localStorage.removeItem('user_roles')
                        resolve(response)
                    })
                    .catch(error => {
                        const notification = {
                            type: 'error',
                            message: 'Invalid data' + error.message
                        }
                        context.dispatch('notification/add', notification, { root: true })
                    })
            })
        },
        fetchAuthUser(context) {
            axios.get('/api/me?token=' + this.state.token)
                .then(response => {
                    context.commit('getUser', response.data)
                })
                .catch(error => {
                    console.log(error)
                })
        },
        getSingleUser(context, id) {
            return new Promise((resolve, reject) => {
                axios.get('/api/user/' + id + '?token=' + this.state.token)
                    .then(response => {
                        context.commit('setUserForProfile', response.data)
                        resolve(response)
                    })
                    .catch(error => {
                        reject(error)
                        console.log(error)
                    })
            })
        },
        fetchAllRoles(context) {
            axios.get('/api/roles?token=' + this.state.token )
                .then(response => {
                    context.commit('setAllRoles', response.data)
                })
                .catch(error => {
                    console.log(error)
                })
        },
        getUsers(context, page = 0) {
            axios.get('/api/users?token=' + this.state.token, {
                    params: {
                        page
                    }
                })
                .then( response => {
                    context.commit('getAllUsers', response.data.data)
                    context.commit('setUserMeta', response.data.meta)
                })
                .catch(error => {
                        console.log(error)
                })
        },
        updateUser(context, data) {
            return new Promise((resolve, reject) => {
                axios.put('/api/user/'+ data.id + '?token=' + this.state.token,{
                    name: data.name,
                    surname: data.surname,
                    email: data.email,
                    password: data.password,
                    role: data.role,
                    type_id: data.type_id
                })
                    .then (response => {
                        context.commit('updateUser', response.data)
                        const notification = {
                            type: 'success',
                            message: 'Successfully updated user'
                        }
                        context.dispatch('notification/add', notification, { root: true })
                        resolve(response)
                    })
                    .catch( error => {
                        const notification = {
                            type: 'error',
                            message: '' + JSON.stringify(error.response.data.message),
                        }
                        reject(error)
                        context.dispatch('notification/add', notification, { root: true })
                        throw error
                    })
            })
        },
        updateUserPassword(context, payload) {
            return new Promise((resolve, reject) => {
                axios.patch('/api/user/'+ payload.id + '/password' + '?token=' + this.state.token, {
                    password: payload.password
                })
                .then(response => {
                    context.commit('updateUser', response.data)
                        const notification = {
                            type: 'success',
                            message: 'Successfully updated password'
                        }
                        context.dispatch('notification/add', notification, { root: true })
                        resolve(response)
                    })
                .catch(error => {
                    reject(error)
                    const notification = {
                        type: 'success',
                        message: 'Could not update'
                    }
                    context.dispatch('notification/add', notification, { root: true })
                    resolve(response)
                })
            })
        },
        updateUserProfileImage(context, payload) {
            return new Promise((resolve, reject) => {
                axios.put('/api/user/image/' + payload.id + '?token=' + this.state.token, {
                    image: payload.image
                }) 
                .then(response => {
                    console.log(response.data)
                    resolve(response)
                    context.commit('updateUserImage', response.data.data)
                    const notification = {
                        type: 'success',
                        message: 'Successfully updated photo'
                    }
                    context.dispatch('notification/add', notification, { root: true })
                })
                .catch(error => {
                    reject(error)
                    const notification = {
                        type: 'error',
                        message: 'Could not update'
                    }
                    context.dispatch('notification/add', notification, { root: true })
                    throw error
                }) 
            })
        }, 
        deleteUser(context , id) {
            return new Promise((resolve, reject) => {
                axios.delete('/api/user/'+ id + '?token=' + this.state.token)
                    .then(response => {
                        resolve(response)
                        context.commit('removeUserProfile')
                        const notification = {
                            type: 'error',
                            message: 'User deleted'
                        }
                        dispatch('notification/add', notification, { root: true })
                    })
                    .catch( error => {
                        reject(error)
                        const notification = {
                            type: 'error',
                            message: '' + JSON.stringify(error.response.data.message),
                        }
                        dispatch('notification/add', notification, { root: true })
                            throw error
                    })
            })
        },
        addVisit(context, data) {
            return new Promise( (resolve,reject) => {
                axios.post('/api/visits?token=' + this.state.token ,{
                    user_id: data.id,
                    arrived_at: data.arrived_at,
                    planned_time:  data.planned_time,
                    planned: data.planned,
                    image: data.image,
                    left_at: data.left_at
                })
                    .then(response => {
                            context.commit('pushVisit', response.data.data)
                        const notification = {
                            type: 'success',
                            message: 'Visit created',
                        }
                        context.dispatch('notification/add', notification, { root: true })
                        resolve(response)
                    })
                    .catch( error => {
                        reject(error)
                        const notification = {
                            type: 'error',
                            message: '' + error.response.data.message[0],
                        }
                        context.dispatch('notification/add', notification, { root: true })
                        throw error
                    })
            })
        },
        editVisit(context, payload) {
        return new Promise( (resolve, reject) => {
            axios.put('/api/visit/'+ payload.id + '?token=' + this.state.token, {
                left_at: payload.leftAt
            })
                .then( response => {
                    resolve(response)
                    context.commit('updateVisit', response.data)
                    const notification = {
                        type: 'success',
                        message: 'Visit edited successfully',
                    }
                    context.dispatch('notification/add', notification, { root: true })
                })
                .catch(error => {
                    reject(error)
                    const notification = {
                        type: 'error',
                        message: '' + error.response.data.message[0],
                    }
                    context.dispatch('notification/add', notification, { root: true })
                    throw error
                })
            })
            
        },
        getVisit(context, id) {
            return new Promise((resolve, reject) => {
                axios.get('/api/visit/' + id )
                    .then(response => {
                        resolve(response)
                    })
                    .catch(error => {
                        reject(error)
                    })
            })
        },
        closeVisit(context, payload) {
            return new Promise((resolve, reject) => {
                axios.put('/api/close/visit/'+ payload.id + '?token=' + this.state.token, {
                    left_at: payload.left_at
                })
                .then(response => {
                    context.commit('removeVisit',payload.id)
                    const notification = {
                        type: 'success',
                        message: 'Visit closed successfully',
                    }
                    context.dispatch('notification/add', notification, { root: true })

                    const visitNotification = {
                        visitId: payload.id
                    }
                    context.dispatch('visitNotification/add', visitNotification, {root: true})

                    resolve(response)
                })
                .catch(error => {
                    reject(error)
                    const notification = {
                        type: 'error',
                        message: '' + error.response.data.message[0],
                    }
                    context.dispatch('notification/add', notification, { root: true })
                    throw error
                })
            })
        },
        checkIn(context, data) {
            return new Promise((resolve,reject) => {
                axios.post('/api/checkin?token='+ this.state.token,{
                    planned: data.planned,
                    planned_time: data.planned_time,
                    user_id: data.id
                })
                .then(response => {
                    context.commit('pushVisit', response.data.data)
                const notification = {
                    type: 'success',
                    message: 'Visit created',
                }
                context.dispatch('notification/add', notification, { root: true })
                resolve(response)
                })
                .catch( error => {
                    reject(error)
                    const notification = {
                        type: 'error',
                        message: '' + error.response.data.message[0],
                    }
                    context.dispatch('notification/add', notification, { root: true })
                    throw error
                })
            })
        },
        makeNormalVisit(context, data) {
            axios.put('/api/visit/update/planned/' + data.id + '?token=' + this.state.token, {
                arrived_at: data.arrived_at,
                is_planned: data.is_planned,
                planned_time: data.planned_time,
                description: data.description
            })
            .then(response => {
                context.commit('removeVisit', id)
            })
        },
        updateVisit(context, data) {
            return new Promise((resolve, reject) =>  {
                axios.put('/api/visit/'+ data.id +'/update?token=' + this.state.token, {
                    guest_id: data.guest_id,
                    arrived_at: data.arrived_at,
                    planned: data.planned,
                    planned_time: data.planned_date,
                    left_at: data.left_at,
                    description: data.description
                })
                .then(response => {
                    console.log(response.data)
                    context.commit('updateVisit', response.data.data)
                    resolve(response)
                })
                .catch(error => {
                    reject(error)
                })
            })
        },
        deleteVisit(context, id) {
            axios.delete('/api/visit/'+ id + this.state.token)
                .then(response => {
                    context.commit('removeVisit', id)
                    
                    console.log(response)
                })
                .catch(error => {
                    console.log(error)
                })
        },
        plannedVisits(context, page = this.$route.query.params) {
            axios.get('/api/visits/planned?token='+  this.state.token, {
                params: {
                    page
                }
            })
            .then(response => {
                context.commit('plannedVisits',response.data.data)
                context.commit('plannedVisitsMeta',response.data.meta)
            })
        },
        plannedVisitsForUser(context, id, page = 1) {
            axios.get('/api/visits/planned/' + id + '?token=' + this.state.token, {
                params: {
                    page
                }
            })
            .then(response=> {
                context.commit('plannedVisitsForUser', response.data.data)
                context.commit('userVisitsMeta', response.data.meta)
            })
        },
        userVisits(context, id, page = 1) {
            axios.get('/api/visits/notplanned/' + id + '?token=' + this.state.token , {
                params: {
                    page
                }
            })
            .then(response=> {
                console.log(response)
                context.commit('userVisits', response.data.data)
                context.commit('userVisitsMeta', response.data.meta)
            })
        },
        assignFilter(context, filter) {
            context.commit('filterFilter', filter)
        },
        filteredVisits(context, page = 1) {
            let filter = this.state.filter
            axios.get('/api/visits/filtered', {
                    params: {
                        page, filter
                    }
                })
                .then( response => {

                    context.commit('setFilteredVisits', response.data.data)
                    context.commit('activeVisitsMeta', response.data.meta)
                    context.commit('removeMeta')
                })
                .catch( error => {
                    console.log(error)
                })
        },
        // ----END VISITS ------------       
        //Meetings ---->>>
        allMeetings(context) {
            axios.get('/api/meetings')
                .then(response => {
                        context.commit('getMeetings', response.data)
                    })
                .catch(error => {
                    console.log(error)
                })
        },
        todayMeetings(context, page = 1) {
            axios.get('/api/todayMeetings',{
                    params: {
                        page
                    }
                })
                .then(response => {
                    context.commit('getMeetings', response.data.data)
                    context.commit('setMeetingsMeta', response.data.meta)
                })
                .catch(error => {
                    console.log(error)
                })
        },
        thisWeekMeetings(context,page = 1) {
            axios.get('/api/thisWeekMeetings',{
                    params: {
                        page
                    }
                })
                .then(response => {
                    context.commit('getMeetings', response.data.data)
                    context.commit('setMeetingsMeta', response.data.meta)
                })
                .catch(error => {
                    console.log(error)
                })
        },
        thisMonthMeetings(context, page = 1) {
            axios.get('/api/thisMonthMeetings',{
                    params: {
                        page
                    }
                })
                .then(response => {
                    context.commit('getMeetings', response.data.data)
                    context.commit('setMeetingsMeta', response.data.meta)
                })
                .catch(error => {
                    console.log(error)
                })
        },
        createMeeting(context, data) {
            return new Promise ((resolve, reject) => {
                axios.post('/api/meeting/create?token=' + this.state.token , {
                    name: data.name,
                    room_id: data.room_id,
                    description: data.description,
                    start_time: data.start_time
                })
                    .then(response => {
                            console.log(response)
                            context.commit('pushMeeting', response.data.data)
                            const notification = {
                                type: 'success',
                                message: 'Meeting created',
                            }
                            resolve(response)
                            context.dispatch('notification/add', notification, { root: true })
                        })
                    .catch( error => {
                        // console.log(error)
                        const notification = {
                            type: 'error',
                            message: '' + JSON.stringify(error.response.data.message),
                        }
                        reject(error)
                        context.dispatch('notification/add', notification, { root: true })
                        throw error
                    })
            })
        },
        deleteMeeting() {
            axios.delete('/api/meeting/'+ id + this.state.token)
            .then(response => {
                context.commit('removeMeeting', id)
                console.log(response)
            })
            .catch(error => {
                console.log(error)
            })
        },
        updateMeeting(context, data) {
            return new Promise( (resolve, reject) => {
                axios.patch('/api/meeting/'+ data.id + '?token=' + this.state.token, {
                    name: data.name,
                    participants: data.participants,
                    room_id: data.room_id,
                    description: data.description,
                    start_time: data.start_time    
                })
                    .then( response => {
                        resolve(response)
                        context.commit('updateMeeting', response.data)
                        const notification = {
                            type: 'success',
                            message: 'Meeting edited successfully',
                        }
                        context.dispatch('notification/add', notification, { root: true })
                    })
                    .catch(error => {
                        reject(error)
                        const notification = {
                            type: 'error',
                            message: '' + error.response.data.message[0],
                        }
                        context.dispatch('notification/add', notification, { root: true })
                        throw error
                    })
                })
        },
        fetchUserMeetings(context, id, page = 1) {
            axios.get('/api/meetings/user/'+ id + '?token=' + this.state.token, {
                params: {
                    page
                }
            })
                .then(response => {
                    context.commit('setMeetingsWithUsers', response.data.data)
                    context.commit('setMeetingsMeta', response.data.meta)
                })
                .catch(error => {
                    console.log(error)
                })
        },
        //END Meetings
        allContracts(context, id) {
            axios.get('/api/contracts/' + id + '?token=' + this.state.token)
                .then(response => {
                    console.log(response.data)
                    context.commit('setContracts', response.data)
                })
                .catch(error => {
                    
                })
        },
        addContract(context, data) {
            axios.post('/api/contract?token=' + this.state.token, {
                name: data.name,
                contract: data.contract,
                user_id: data.user_id
            })
            .then(response => {
                console.log(response.data)
                context.commit('pushContract', response.data)
                    const notification = {
                        type: 'success',
                        message: 'Contract added',
                    }
                    context.dispatch('notification/add', notification, { root: true })
                    resolve(response)
            })
        },
        removeContract(context, id) {
            axios.delete('/api/contract/' + id + '?token=' + this.state.token)
                then(response => {
                    context.commit('removeContract', id)
                    const notification = {
                        type: 'success',
                        message: 'Contract added',
                    }
                    context.dispatch('notification/add', notification, { root: true })
                    resolve(response)
                })
        }
    }
})