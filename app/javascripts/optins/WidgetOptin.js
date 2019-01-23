import { h, render, Component } from 'preact';
import utils from './utils';
import Form from './elements/Form'

class WidgetOptin extends Component {


    render() {
        return (
            // This first div should get the UID as an ID. This ID will also be used to prefix custom css from user
            <div id="DELI-Optin" className="DELI-Orientation DELI-Shortcode">
                <Form {...this.props} />
            </div>
        )
    }
}

export default WidgetOptin;
