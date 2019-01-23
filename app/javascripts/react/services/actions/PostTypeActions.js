import {
    REQUEST_GET_POST_TYPES,
    REQUEST_GET_POSTS,
    REQUEST_GET_POST,
    REQUEST_GET_POST_TO_WP_POST,
    REQUEST_IMPORT_POSTS_WP
} from 'javascripts/react/constants/PostTypeConstants'


import {
    transformRequestToAjaxWordPress
} from 'javascripts/react/helpers/transformRequestToAjaxWordPress'


class PostTypeActions  {

    constructor(){
        this.getPostTypes          = this.getPostTypes.bind(this)
        this.getPosts              = this.getPosts.bind(this)
        this.getPost               = this.getPost.bind(this)
        this.getPostToWPPost       = this.getPostToWPPost.bind(this)
        this.importPostsWP         = this.importPostsWP.bind(this)
        
    }

    getPostTypes(){
        
        return {
            type : REQUEST_GET_POST_TYPES,
            payload: {
                client: 'default',
                request:{
                    method: 'POST',
                    url: "/admin-ajax.php",
                    data:  {
                        action : "delipress_get_post_types",
                        _wpnonce_ajax: WPNONCE_AJAX
                    },
                    headers : { 
                        'Content-Type':'application/x-www-form-urlencoded; charset=UTF-8',
                        'Accept' : '*/*'
                    },
                    transformRequest : transformRequestToAjaxWordPress
                }
            }
        }
    }

    getPosts(params){
        
        return {
            type : REQUEST_GET_POSTS,
            payload: {
                client: 'default',
                request:{
                    method: 'POST',
                    url: "/admin-ajax.php",
                    data:  {
                        action : "delipress_get_posts",
                        post_type: _.isObject(params.post_type) ? params.post_type.value : params.post_type,
                        s: (!_.isUndefined(params.s) ) ? params.s : "",
                        _wpnonce_ajax: WPNONCE_AJAX
                    },
                    headers : { 
                        'Content-Type':'application/x-www-form-urlencoded; charset=UTF-8',
                        'Accept' : '*/*'
                    },
                    transformRequest : transformRequestToAjaxWordPress
                }
            }
        }
    }

    getPost(params, onSuccess = function(){}){
     
        return {
            type : REQUEST_GET_POST,
            payload: {
                client: 'default',
                request:{
                    method: 'POST',
                    url: "/admin-ajax.php",
                    data:  {
                        action : "delipress_get_posts",
                        post_id: params.post_id,
                        _wpnonce_ajax: WPNONCE_AJAX
                    },
                    headers : { 
                        'Content-Type':'application/x-www-form-urlencoded; charset=UTF-8',
                        'Accept' : '*/*'
                    },
                    transformRequest : transformRequestToAjaxWordPress,
                    onSuccess : onSuccess
                }
            }
        }
    }
   
    getPostToWPPost(params, extras, onSuccess = function(){} ){
        const _params = jQuery.param(_.extend({
            action : "delipress_get_post",
            _wpnonce_ajax: WPNONCE_AJAX
        }, params))
        
        return {
            type : REQUEST_GET_POST_TO_WP_POST,
            payload: {
                client: 'default',
                request:{
                    method: 'POST',
                    url: "/admin-ajax.php",
                    data:  _params,
                    headers : { 
                        'Content-Type':'application/x-www-form-urlencoded; charset=UTF-8',
                        'Accept' : '*/*'
                    },
                    onSuccess: onSuccess
                },
                extras: extras
            }
        }
    }
   
    importPostsWP(params, extras, onSuccess = function(){} ){

        const _params = jQuery.param({
            action : "delipress_import_posts_wp",
            posts: params.posts,
            config: params.config,
            _wpnonce_ajax: WPNONCE_AJAX
        })

        return {
            type : REQUEST_IMPORT_POSTS_WP,
            payload: {
                client: 'default',
                request:{
                    method: 'POST',
                    url: "/admin-ajax.php",
                    data:  _params,
                    headers : { 
                        'Content-Type':'application/x-www-form-urlencoded; charset=UTF-8',
                        'Accept': 'application/json, text/javascript, */*'
                    },
                    onSuccess: onSuccess
                },
                extras: extras
            }
        }
    }

}

export default PostTypeActions
