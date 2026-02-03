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

    const nullableBoolean = (value: unknown): boolean | null => {
        if (value === null || value === undefined) return null
        return Boolean(value);
    }

    const translateStatus = (status: string) => {
        switch (status) {
            case 'sended':
                return 'Enviado'
            case 'approved':
                return 'Aprovado'
            case 'rejected':
                return 'Reprovado'
            default:
                return status
        }
    }

    return {
        formatDate,
        nullableBoolean,
        translateStatus,
    };
}
