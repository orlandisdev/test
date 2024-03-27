import { forwardRef, useEffect, } from 'react';

export default forwardRef(function TextInput({ type = 'text', className = '', isFocused = false, ...props }) {
    //const input = ref ? ref : useRef();

   // useEffect(() => {
   //     if (isFocused) {
   //         input.current.focus();
   //     }
   // }, []);

    return (
        <input
            {...props}
            type={type}
            className={
                'bg-white ' +
                className
            }
        />
    );
});
