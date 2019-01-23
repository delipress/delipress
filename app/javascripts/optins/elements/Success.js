import { h, render, Component } from "preact"

// Tell Babel to transform JSX into h() calls:
/** @jsx h */

export default class Success extends Component {
    render() {
        return (
            <div
                className="DELI-textBloc DELI-success"
                dangerouslySetInnerHTML={{ __html: this.props.text }}
            />
        )
    }
}
