import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

export function useRoles() {
    const page = usePage();
    const roles = computed(() => page.props.roles as string[]);
    const permissions = computed(() => page.props.permissions as string[]);

    const is = (role: string): boolean => {
        return roles.value.includes(role);
    };

    const can = (permission: string): boolean => {
        return permissions.value.includes(permission);
    };

    const isAny = (...rolesToCheck: string[]): boolean => {
        return rolesToCheck.some(role => roles.value.includes(role));
    };

    const isAll = (...rolesToCheck: string[]): boolean => {
        return rolesToCheck.every(role => roles.value.includes(role));
    };

    const canAny = (...permissionsToCheck: string[]): boolean => {
        return permissionsToCheck.some(permission => permissions.value.includes(permission));
    };

    const canAll = (...permissionsToCheck: string[]): boolean => {
        return permissionsToCheck.every(permission => permissions.value.includes(permission));
    };

    const hasMultipleRoles = computed<boolean>(() => {
        return roles.value.length > 1;
    });

    return {
        is,
        can,
        isAny,
        isAll,
        canAny,
        canAll,
        hasMultipleRoles
    };
}
