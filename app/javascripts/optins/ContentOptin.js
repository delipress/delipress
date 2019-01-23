import { h, render, Component } from 'preact'
import utils from './utils'

import Form from './elements/Form'

class ContentOptin extends Component {

    constructor(props){
        super(props)
        
        this.refs = {
            emailField: ""
        }
    }
    // componentWillMount(){
    //     COOKIE_NAME = COOKIE_NAME + this.props.uid;

    //     const cookie = utils.dOptinVisible(COOKIE_NAME, this.props.visibility);

    //     // Check if the component should render or not
    //     if (this.props.delayed) {
    //         window.setTimeout(() => {
    //             this.setState({isHidden: cookie});
    //         }, this.props.delayed * 1000)
    //     }
    //     else{
    //         this.setState({isHidden: cookie});
    //     }

    // }

    handleSubmit(e){
        e.preventDefault();
        
        const {
            handleSubmit
        } = this.props

        handleSubmit({
            emailField: this.refs.emailField.value
        })
    }


    render() {
        const info = {
            config : this.props.config,
            parentState : this.props.parentState,
            handleSubmit: this.props.handleSubmit,
            handleChange: this.props.handleChange
        }

        return (
            // This first div should get the UID as an ID. This ID will also be used to prefix custom css from user
            <div id="DELI-Optin" className="DELI-Content">
                <Form info={info}></Form>
            </div>
        )
    }
}

export default ContentOptin;
