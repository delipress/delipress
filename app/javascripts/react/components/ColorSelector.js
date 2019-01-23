import React, { Component, cloneElement } from "react"
import PropTypes from "prop-types"
import { shallowEqual } from "javascripts/react/helpers/shallowEqual"
import { connect } from "react-redux"
import { compose, bindActionCreators } from "redux"
import { BlockPicker, SketchPicker } from "react-color"
import reactCSS from "reactcss"

class ColorSelector extends Component {
    constructor(props) {
        super(props)

        this.state = {
            color: this.props.color
        }

        this.defaultColorsNoStorage = [
            "#CCCCCC",
            "#333333",
            "#FF6900",
            "#7BDCB5",
            "#8ED1FC",
            "#FCB900",
            "#795548",
            "#FFFFFF",
            "#4D4D4D",
            "#000000",
            "#EB144C",
            "#00D084",
            "#0693E3",
            "#F78DA7",
            "#607D8B"
        ]

        this._handleChange = this._handleChange.bind(this)
        this.handleClose = this.handleClose.bind(this)
        this._handleKeyDown = this._handleKeyDown.bind(this)
        this.handleClick = this.handleClick.bind(this)
        this._handleChangeComplete = this._handleChangeComplete.bind(this)
    }

    componentWillMount() {
        this.setState({
            displayColorPicker: false,
            color: this.props.color
        })
    }

    componentWillReceiveProps(nextProps) {
        this.setState({
            color: nextProps.color
        })
    }

    handleClick() {
        this.setState({ displayColorPicker: !this.state.displayColorPicker })
        document.addEventListener("keydown", this._handleKeyDown)
    }

    handleClose() {
        document.removeEventListener("keydown", this._handleKeyDown)
        this.setState({ displayColorPicker: false })
    }

    _handleChange(color) {
        const { handleChange, idSelector, typeColor } = this.props

        this.setState({
            color: color
        })

        if (!_.isUndefined(idSelector) && _.isUndefined(handleChange)) {
            jQuery(idSelector).css({
                [typeColor]: `rgba(${color.rgb.r}, ${color.rgb.g}, ${color.rgb
                    .b}, ${color.rgb.a})`
            })
        } else if (!_.isUndefined(handleChange)) {
            handleChange(color)
        }
    }

    _handleChangeComplete(color) {
        const { handleChangeComplete, idSelector, typeColor } = this.props
        
        let colorsStorage = JSON.parse(localStorage.getItem('dp_default_colors') );

        if(_.isNull(colorsStorage)){
            colorsStorage = []
        }
        
        if(!_.contains(colorsStorage,color.hex)){
            colorsStorage.unshift(color.hex)
        }
        
        
        if(colorsStorage.length < 16){
            const defaultColors = _.clone(this.defaultColorsNoStorage)
            colorsStorage = _.union(colorsStorage, defaultColors.splice(0, (16-colorsStorage.length) ) )
        }
        else{
            colorsStorage.splice(16, colorsStorage.length - 16)
        }

        localStorage.setItem('dp_default_colors', JSON.stringify(colorsStorage) );
        
        this.setState({
            color: color
        })

        handleChangeComplete(color)
    }

    _handleKeyDown(e) {
        if (e.charCode == 13 || e.keyCode == 13) {
            this.handleClose()
        }
    }

    render() {
        const { color } = this.state

        if (_.isUndefined(color)) {
            return false
        }

        const styles = reactCSS({
            default: {
                color: {
                    background: `rgba(${color.rgb.r}, ${color.rgb.g}, ${color
                        .rgb.b}, ${color.rgb.a})`,
                    cursor: "pointer"
                },
                popover: {
                    position: "absolute",
                    zIndex: "9999",
                    left: 0
                },
                cover: {
                    position: "fixed",
                    top: "0px",
                    right: "0px",
                    bottom: "0px",
                    left: "0px"
                }
            }
        })

        let _picker = false
        switch (this.props.picker) {
            case "block":
                _picker = (
                    <BlockPicker
                        color={color.hex}
                        onChange={this._handleChange}
                        onChangeComplete={this._handleChangeComplete}
                    />
                )
                break
            case "sketch":
                let colorsStorage = JSON.parse(localStorage.getItem('dp_default_colors') );
                if(_.isNull(colorsStorage)){
                    colorsStorage = this.defaultColorsNoStorage
                }

                _picker = (
                    <SketchPicker
                        disableAlpha={true}
                        presetColors={colorsStorage}
                        color={color.rgb}
                        onChange={this._handleChange}
                        onChangeComplete={this._handleChangeComplete}
                    />
                )
                break
        }

        return (
            <div>
                <div
                    className="delipress__simplepicker delipress__simplepicker--tiny"
                    onClick={this.handleClick}
                    style={styles.color}
                />
                {this.state.displayColorPicker
                    ? <div style={styles.popover}>
                          <div
                              style={styles.cover}
                              onClick={this.handleClose}
                          />
                          {_picker}
                      </div>
                    : null}
            </div>
        )
    }
}

ColorSelector.propTypes = {
    handleChange: PropTypes.func,
    handleChangeComplete: PropTypes.func.isRequired,
    picker: PropTypes.string.isRequired,
    idSelector: PropTypes.string,
    typeColor: PropTypes.string
}

export default ColorSelector
