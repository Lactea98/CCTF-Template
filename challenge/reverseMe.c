#include <stdio.h>

#define LEN 25

void encrypt(char *flag){
    for(int i=0; i<LEN; i++){
        int ascii = flag[i];
        
        if(65 <= ascii && ascii <= 80){
            ascii += 15;
            ascii ^= 15;
        }
        else if(80 < ascii && ascii <= 90){
            ascii -= 20;
            ascii ^= 20;
        }
        else if(90 < ascii && ascii <= 105){
            ascii *= 3;
            ascii ^= 7;
        }
        else if(105 < ascii && ascii <= 122){
            ascii -= 40;
        }
        else{
            // pass
        }
        printf("%c", ascii);
    }
}

int main(){
    char flag[LEN] = "???????????";
    encrypt(flag);
}