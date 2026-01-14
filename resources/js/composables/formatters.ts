import moment from "moment";

export function formatters() {
    const formatDate = (str = '', format = null, outputFormat = 'YYYY-MM-DD HH:mm:ss') => {
        if (str) {
            if (format == null) {
                return moment(str).format(outputFormat);
            }
            return moment(str, format).format(outputFormat);
        }
        return str;
    }

    return {
        formatDate,
    };
}
