export const namespaced = true

export const state = {
    visitNotifications: []
}

let nextId = 1

export const mutations = {
    push(state, visitNotification) {
        state.visitNotifications.push({
            ...visitNotification,
            id: nextId++
        })
    },
    delete(state, visitNotificationToRemove) {
        state.visitNotifications = state.visitNotifications.filter( 
            visitNotification => visitNotification.id !== visitNotificationToRemove.id)
    }
}

export const actions = {
    add({commit}, visitNotification) {
        commit('push', visitNotification)
    },
    remove({commit}, visitNotificationToRemove){
        commit('delete', visitNotificationToRemove)
    },
}