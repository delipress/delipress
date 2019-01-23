export function createOnChangeTeampltePreview(templateId) {
    return new CustomEvent('onChangeTeampltePreview',
        {
            "detail": {
                "template_id": templateId
            }
        }
    );
}   