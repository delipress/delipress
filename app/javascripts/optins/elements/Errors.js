import { h, render, Component } from "preact"

// Tell Babel to transform JSX into h() calls:
/** @jsx h */

export default class Errors extends Component {
    render() {
        return (
            <div
                className="DELI-textBloc DELI-error"
                dangerouslySetInnerHTML={{ __html: this.props.text }}
            />
        )
    }
}
