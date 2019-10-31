export const namespaced = true

export const state = {
    meetingNotifications: []
}

let nextId = 1

export const mutations = {
    push(state, meetingNotification) {
        state.meetingNotifications.push({
            ...meetingNotification,
            id: nextId++
        })
    },
    delete(state, meetingNotificationToRemove) {
        state.meetingNotifications = state.meetingNotifications.filter( 
            meetingNotification => meetingNotification.id !== meetingNotificationToRemove.id)
    }
}

export const actions = {
    add({commit}, meetingNotification) {
        commit('push', meetingNotification)
    },
    remove({commit}, meetingNotificationToRemove){
        commit('delete', meetingNotificationToRemove)
    }
}