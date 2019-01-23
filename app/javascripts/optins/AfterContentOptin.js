import { h, render, Component } from 'preact';
import utils from './utils';
import resetcss from './styles/reset';
import Form from './elements/Form'

// Tell Babel to transform JSX into h() calls:
/** @jsx h */

class AfterContentOptin extends Component {

    render() {

        return (
            // This first div should get the UID as an ID. This ID will also be used to prefix custom css from user
            <div id="DELI-Optin" className="DELI-Orientation DELI-AfterContent">
                <Form {...this.props} />
            </div>
        )
    }
}

export default AfterContentOptin
