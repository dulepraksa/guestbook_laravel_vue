export const namespaced = true

export const state = {
    notifications: []
}

let nextId = 1

export const mutations = {
    push(state, notification) {
        state.notifications.push({
            ...notification,
            id: nextId++
        })
    },
    delete(state, notificationToRemove) {
        state.notifications = state.notifications.filter( 
            notification => notification.id !== notificationToRemove.id)
    }
}

export const actions = {
    add({commit}, notification) {
        commit('push', notification)
    },
    remove({commit}, notificationToRemove){
        commit('delete', notificationToRemove)
    }
}