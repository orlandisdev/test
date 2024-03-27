export default function InputLabel({ value, className = '', children, ...props }) {
    return (
        <label {...props} className={`font-bold text-blue-500 ` + className}>
            {value ? value : children}
        </label>
    );
}
