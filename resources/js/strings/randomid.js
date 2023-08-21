let id = 0;
export default function(prefix = 'uid-') {
    id += 1;
    return prefix+id
}
