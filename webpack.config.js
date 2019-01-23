var path = require("path")

module.exports = {
    entry: "./app/javascripts/optins/BaseOptin.js",

    output: {
        path: path.join(__dirname, "public/js"),
        filename: "optins.js"
    },

    module: {
        rules: [
            {
                test: /\.jsx?/i,
                loader: "babel-loader",
                options: {
                    presets: ["env"],
                    plugins: [["transform-react-jsx", { pragma: "h" }]]
                }
            }
        ]
    }
}
