import { findDOMNode } from 'react-dom';

export function getAfterOrBefore(monitor, component){
    const { y }           = monitor.getClientOffset();
    const { top, height } = findDOMNode(component).getBoundingClientRect();

    if (y < top + height/2) {
        return "before"
    } else {
        return "after"
    }
}