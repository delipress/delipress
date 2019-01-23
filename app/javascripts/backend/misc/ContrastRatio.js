export function contrastRatio(el) {
    const rgb = getComputedStyle(el)["backgroundColor"]
    let rgbArr = []
    rgbArr.push(rgb.replace(/.+\((\d{1,3}),\s(\d{1,3}),\s(\d{1,3})\)/gm, "$1"))
    rgbArr.push(rgb.replace(/.+\((\d{1,3}),\s(\d{1,3}),\s(\d{1,3})\)/gm, "$2"))
    rgbArr.push(rgb.replace(/.+\((\d{1,3}),\s(\d{1,3}),\s(\d{1,3})\)/gm, "$3"))

    rgbArr.forEach((c, index) => {
        c = c / 255
        if (c <= 0.03928) {
            c = c / 12.92
        } else {
            c = Math.pow((c + 0.055) / 1.055, 2.4)
        }
        rgbArr[index] = c
    })

    const L = 0.2126 * rgbArr[0] + 0.7152 * rgbArr[1] + 0.0722 * rgbArr[2]

    const result = L > 0.179 ? 0 : 1
    return result
}
