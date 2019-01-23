import { h, render, Component } from 'preact';
import utils from './utils';

// Tell Babel to transform JSX into h() calls:
/** @jsx h */

class LockedOptin extends Component {

    constructor(props, ctx) {
        super(props, ctx);
    }

    render() {

        return (
            // This first div should get the UID as an ID. This ID will also be used to prefix custom css from user
            <div>TEST</div>
        )
    }
}

export default LockedOptin;
