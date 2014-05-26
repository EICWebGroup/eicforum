var UndoMemory = function () {
    var index = 0;
    var globalMemory = [];

    return {
        store: function (str) {
            if (globalMemory.length <= index) {

                // if index is bigger or equal to globalMemory length, push array

                globalMemory.push(str);
                index++;
            } else {
                globalMemory[index] = str;
                index++;
            }
        },

        undo: function () {
            
            if (index > 0) {
                index--;
                return globalMemory[index];
            } else {
                index = 0;
                return globalMemory[index];
            }
        },

        redo: function () {
            if (index < (globalMemory.length-1)) {
                index++;
                return globalMemory[index];
                        } else {
                index = globalMemory.length - 1;
                return globalMemory[index];
            }
        },
        hasRedo: function(){
            if(index == globalMemory.length) return false;
            else return true;
        },
        hasUndo: function(){
            if(index == 0)return false;
            else return true;
        },
        CurrentIndex: function () { return index-1; },

        memoryInfo: function () { return globalMemory; }
    };
};
