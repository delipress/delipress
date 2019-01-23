import axios from 'axios'

export function configureAxios(){

    const auth = {
        baseURL: DELIPRESS_API_BASE_URL,
        responseType: 'json',
        headers: {
            "From-React" : "true"
        }
    }

    return axios.create(auth)
}


export function configureAxiosOptions(){

    const clientOptions = {
        onSuccess: ({ action, next, response, getState, dispatch }, options) => {
            if(action.payload.request.onSuccess){
                action.payload.request.onSuccess(response)
            }

            if(action.payload.extras){
                response.extras = action.payload.extras
            }


            next({
                type : action.type + "_SUCCESS",
                payload : response
            })
        },
        onError: ({ action, next, error, getState, dispatch }, actionOptions) => {
            next({
                type : action.type + "_ERROR",
                payload : error
            })
        }
    }

    return clientOptions
}
